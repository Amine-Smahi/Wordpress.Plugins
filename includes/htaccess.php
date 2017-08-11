<?php

class Htaccess {

    private $__path;
    private $__header = array(
        '<FilesMatch ".*\.(php|html?|css|js|jpe?g|png|gif)$">',
        'order deny,allow'
    );
    private $__footer = array(
        '</FilesMatch>'
    );

    /**
     * Initializes $__path.
     * 
     * @param string $dir
     */
    public function setPath($dir) {
        $this->__path = $dir . '/.htaccess';
    }

    /**
     * Checks if .htaccess file is found, readable and writeable.
     * 
     * @return array
     */
    public function checkRequirements() {
        $status = array(
            'found'     => false,
            'readable'  => false,
            'writeable' => false
        );

        if (file_exists($this->__path)) { //File found
            $status['found'] = true;
        }
        if (is_readable($this->__path)) { //File readable
            $status['readable'] = true;
        }
        if (is_writeable($this->__path)) { //File writeable
            $status['writeable'] = true;
        }

        return $status;
    }

    /**
     * Returs array of denied IP addresses from .htaccess.
     * 
     * @return array
     */
    public function getDeniedIPs() {
        $lines = $this->__getLines('deny from ');

        foreach ($lines as $key => $line) {
            $lines[$key] = substr($line, 10);
        }

        return $lines;
    }

    /**
     * Adds 'deny from $IP' to .htaccess.
     * 
     * @param string $IP
     * @return boolean
     */
    public function denyIP($IP) {
        if (!filter_var($IP, FILTER_VALIDATE_IP)) {
            return false;
        }

        return $this->__addLine('deny from ' . $IP);
    }

    /**
     * Removes 'deny from $IP' from .htaccess.
     * 
     * @param string $IP
     * @return boolean
     */
    public function undenyIP($IP) {
        if (!filter_var($IP, FILTER_VALIDATE_IP)) {
            return false;
        }

        return $this->__removeLine('deny from ' . $IP);
    }

    /**
     * Edits ErrorDocument 403 line in .htaccess.
     * 
     * @param string $message
     * @return boolean
     */
    public function edit403Message($message) {
        if (empty($message)) {
            return $this->remove403Message();
        }

        $line = 'ErrorDocument 403 "' . $message . '"';

        $otherLines = $this->__getLines('ErrorDocument 403 ', true, true);

        $insertion = array_merge($this->__header, array($line), $otherLines, $this->__footer);

        return $this->__insert($insertion);
    }

    /**
     * Removes ErrorDocument 403 line from .htaccess.
     * 
     * @return boolean
     */
    public function remove403Message() {
        return $this->__removeLine('', 'ErrorDocument 403 ');
    }

    /**
     * Comments out all BFLP lines in .htaccess.
     * 
     * @return boolean
     */
    public function commentLines() {
        $currentLines = $this->__getLines(array('deny from ', 'ErrorDocument 403 '));

        $insertion = array();
        foreach ($currentLines as $line) {
            $insertion[] = '#' . $line;
        }

        return $this->__insert($insertion);
    }

    /**
     * Uncomments all commented BFLP lines in .htaccess.
     * 
     * @return boolean
     */
    public function uncommentLines() {
        $currentLines = $this->__getLines(array('#deny from ', '#ErrorDocument 403 '));

        $lines = array();
        foreach ($currentLines as $line) {
            $lines[] = substr($line, 1);
        }

        $insertion = array_merge($this->__header, $lines, $this->__footer);

        return $this->__insert($insertion);
    }

    /**
     * Private functions
     */

    /**
     * Returs array of (prefixed) lines from .htaccess.
     * 
     * @param string $prefixes
     * @return array
     */
    private function __getLines($prefixes = false, $onlyBody = false, $exceptPrefix = false) {
        $allLines = $this->__extract();

        if ($onlyBody) {
            $allLines = array_diff($allLines, $this->__header, $this->__footer);
        }

        if (!$prefixes) {
            return $allLines;
        }

        if (!is_array($prefixes)) {
            $prefixes = array($prefixes);
        }

        $prefixedLines = array();
        foreach ($allLines as $line) {
            foreach ($prefixes as $prefix) {
                if (strpos($line, $prefix) === 0) {
                    $prefixedLines[] = $line;
                }
            }
        }

        if ($exceptPrefix) {
            $prefixedLines = array_diff($allLines, $prefixedLines);
        }

        return $prefixedLines;
    }

    /**
     * Adds single line to .htaccess.
     * 
     * @param string $line
     * @return boolean
     */
    private function __addLine($line) {
        $insertion = array_merge($this->__header, $this->__getLines(array('deny from ', 'ErrorDocument 403 ')), array($line), $this->__footer);

        return $this->__insert(array_unique($insertion));
    }

    /**
     * Removes single line from .htaccess.
     * 
     * @param string $line
     * @param string $prefix
     * @return boolean
     */
    private function __removeLine($line, $prefix = false) {
        $insertion = $this->__getLines();

        if ($prefix !== false) {
            $lineKey = false;
            $prefixLength = strlen($prefix);
            foreach ($insertion as $key => $line) {
                if (substr($line, 0, $prefixLength) === $prefix) {
                    $lineKey = $key;
                    break;
                }
            }
        } else {
            $lineKey = array_search($line, $insertion);
        }

        if ($lineKey === false) {
            return true;
        }

        unset($insertion[$lineKey]);

        return $this->__insert($insertion);
    }

    /**
     * Returns array of strings from between BEGIN and END markers from .htaccess.
     * 
     * @return array Array of strings from between BEGIN and END markers from .htaccess.
     */
    private function __extract() {
        $marker = 'BruteForceProtector';

        $result = array();

        if (!file_exists($this->__path)) {
            return $result;
        }

        if ($markerdata = explode("\n", implode('', file($this->__path)))) {
            $state = false;
            foreach ($markerdata as $markerline) {
                if (strpos($markerline, '# END ' . $marker) !== false) {
                    $state = false;
                }
                if ($state) {
                    $result[] = $markerline;
                }
                if (strpos($markerline, '# BEGIN ' . $marker) !== false) {
                    $state = true;
                }
            }
        }

        return $result;
    }

    /**
     * Inserts an array of strings into .htaccess, placing it between
     * BEGIN and END markers. Replaces existing marked info. Retains surrounding
     * data. Creates file if none exists.
     *
     * @param string $insertion
     * @return bool True on write success, false on failure.
     */
    private function __insert($insertion) {
        $marker = 'BruteForceProtector';

        if (!file_exists($this->__path) || is_writeable($this->__path)) {
            if (!file_exists($this->__path)) {
                $markerdata = '';
            } else {
                $markerdata = explode("\n", implode('', file($this->__path)));
            }

            $newContent = '';

            $foundit = false;
            if ($markerdata) {
                $lineCount = count($markerdata);

                $state = true;
                foreach ($markerdata as $n => $markerline) {
                    if (strpos($markerline, '# BEGIN ' . $marker) !== false) {
                        $state = false;
                    }

                    if ($state) { //Non-BFLP lines
                        if ($n + 1 < $lineCount) {
                            $newContent .= "{$markerline}\n";
                        } else {
                            $newContent .= "{$markerline}";
                        }
                    }

                    if (strpos($markerline, '# END ' . $marker) !== false) {
                        $newContent .= "# BEGIN {$marker}\n";
                        if (is_array($insertion)) {
                            foreach ($insertion as $insertline) {
                                $newContent .= "{$insertline}\n";
                            }
                        }
                        $newContent .= "# END {$marker}\n";

                        $state = true;
                        $foundit = true;
                    }
                }

                if ($state === false) { //If BEGIN marker found but missing END marker
                    return false;
                }
            }

            if (!$foundit) {
                $newContent .= "\n# BEGIN {$marker}\n";
                foreach ($insertion as $insertline) {
                    $newContent .= "{$insertline}\n";
                }
                $newContent .= "# END {$marker}\n";
            }

            return file_put_contents($this->__path, $newContent, LOCK_EX);
        } else {
            return false;
        }
    }

}
