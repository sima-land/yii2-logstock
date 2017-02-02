<?php
namespace pastuhov\logstock;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;
use yii\log\Logger;
use yii\log\Target;

/**
 * The logstock LogTarget is used to store logs
 */
class LogTarget extends Target
{
    /**
     * @var Module
     */
    public $module;
    public $tag;

    /**
     * {@inheritdoc}
     */
    public $enabled = false;

    /**
     * @param \yii\debug\Module $module
     * @param array $config
     */
    public function __construct($module, $config = [])
    {
        parent::__construct($config);
        $this->module = $module;
        $this->tag = uniqid();
    }

    /**
     * Exports log messages to a specific destination.
     * Child classes must implement this method.
     */
    public function export()
    {
        $path = $this->module->dataPath;
        FileHelper::createDirectory($path, $this->module->dirMode);

        $summary = $this->collectSummary();
        $dataFile = "$path/{$this->tag}.log";
        $data = [];

        $data['log'] = $this->save();

        $data['summary'] = $summary;
        file_put_contents($dataFile, $this->convertArrayToText($data));
        if ($this->module->fileMode !== null) {
            @chmod($dataFile, $this->module->fileMode);
        }

        $indexFile = "$path/index.data";
        $this->updateIndexFile($indexFile, $summary);
    }


    /**
     *
     */
    public function save()
    {
        $messages = $this->filterMessages(
            $this->messages,
            Logger::LEVEL_ERROR | Logger::LEVEL_INFO | Logger::LEVEL_WARNING | Logger::LEVEL_TRACE,
            [],
            [
                'yii\db\Connection::open',
                'yii\db\Connection::close',
            ]
        );
        foreach ($messages as &$message) {
            // exceptions may not be serializable if in the call stack somewhere is a Closure
            if ($message[0] instanceof \Throwable || $message[0] instanceof \Exception) {
                $message[0] = (string)$message[0];
            }
        }
        return ['messages' => $messages];
    }

    /**
     * Updates index file with summary log data
     *
     * @param string $indexFile path to index file
     * @param array $summary summary log data
     * @throws \yii\base\InvalidConfigException
     */
    private function updateIndexFile($indexFile, $summary)
    {
        touch($indexFile);
        if (($fp = @fopen($indexFile, 'r+')) === false) {
            throw new InvalidConfigException("Unable to open debug data index file: $indexFile");
        }
        @flock($fp, LOCK_EX);
        $manifest = '';
        while (($buffer = fgets($fp)) !== false) {
            $manifest .= $buffer;
        }
        if (!feof($fp) || empty($manifest)) {
            // error while reading index data, ignore and create new
            $manifest = [];
        } else {
            $manifest = unserialize($manifest);
        }

        $manifest[$this->tag] = $summary;
        $this->gc($manifest);

        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, serialize($manifest));

        @flock($fp, LOCK_UN);
        @fclose($fp);

        if ($this->module->fileMode !== null) {
            @chmod($indexFile, $this->module->fileMode);
        }
    }

    /**
     * Processes the given log messages.
     * This method will filter the given messages with [[levels]] and [[categories]].
     * And if requested, it will also export the filtering result to specific medium (e.g. email).
     * @param array $messages log messages to be processed. See [[\yii\log\Logger::messages]] for the structure
     * of each message.
     * @param bool $final whether this method is called at the end of the current application
     */
    public function collect($messages, $final)
    {
        $this->messages = array_merge($this->messages, $messages);
        if ($final) {
            $this->export();
        }
    }

    protected function gc(&$manifest)
    {
        if (count($manifest) > $this->module->historySize + 10) {
            $n = count($manifest) - $this->module->historySize;
            foreach (array_keys($manifest) as $tag) {
                $file = $this->module->dataPath . "/$tag.data";
                @unlink($file);
                unset($manifest[$tag]);
                if (--$n <= 0) {
                    break;
                }
            }
        }
    }

    /**
     * Collects summary data of current request.
     * @return array
     */
    protected function collectSummary()
    {
        if (Yii::$app === null) {
            return '';
        }

        $request = Yii::$app->getRequest();
        $entry = '';
        try {
            $entry = $request->getAbsoluteUrl();
        } catch (\Exception $e) {
            $entry = 'console';
        }

        $summary = [
            'tag' => $this->tag,
            'entry' => $entry,
        ];

        return $summary;
    }

    protected function convertArrayToText($array)
    {
        $text = 'Entry: ' . $array['summary']['entry'] . PHP_EOL;

        foreach ($array['log']['messages'] as $key=>$message) {
            $text .= trim($message[0]) . PHP_EOL;
        }

        return $text;
    }

}
