<?php

class Domains
{
    /**
     * @var array
     */
    static protected $list = [];

    /**
     * Domain
     *
     * @var string
     */
    protected $domain = '';

    /**
     * TLD
     *
     * @var string
     */
    protected $TLD = '';

    /**
     * Suffix
     *
     * @var string
     */
    protected $suffix = '';

    /**
     * Name
     *
     * @var string
     */
    protected $name = '';

    /**
     * Sub Domain
     *
     * @var string
     */
    protected $sub = '';

    /**
     * Domain Parts
     *
     * @var array
     */
    protected $parts = [];

    /**
     * Domain constructor.
     *
     * @param string $name
     */
    public function __construct(string $domain)
    {
        if ((strpos($domain, 'http') === 0) || (strpos($domain, 'https') === 0)) {
            return false;
        }

        $this->domain = \mb_strtolower($domain);
        $this->parts = \explode('.', $this->domain);

        if (empty(self::$list)) {
            self::$list = include __DIR__.'/data/data.php';
        }
    }

    /**
     * Return top level domain
     * 
     * @return string
     */
    public function get(): string
    {
        return $this->domain;
    }

    /**
     * Return top level domain
     * 
     * @return string
     */
    public function getTLD(): string
    {
        if ($this->TLD) {
            return $this->TLD;
        }

        $this->TLD = \end($this->parts);
        
        return $this->TLD;
    }

    /**
     * Returns domain public suffix
     * 
     * @return string
     */
    public function getSuffix(): string
    {
        if ($this->suffix) {
            return $this->suffix;
        }
        
        for ($i=3; $i > 0; $i--) {
            $joined = \implode('.', \array_slice($this->parts, $i * -1));

            if (\array_key_exists($joined, self::$list)) {
                $this->suffix = $joined;
                return $joined;
            }
        }

        return '';
    }

    /**
     * Returns registerable domain name
     * 
     * @return string
     */
    public function getRegisterable(): string
    {
        if (!$this->isKnown()) {
            return '';
        }

        $registerable = $this->getName().'.'.$this->getSuffix();
        
        return $registerable;
    }

    /**
     * Returns domain name
     * 
     * @return string
     */
    public function getName(): string
    {
        if ($this->name) {
            return $this->name;
        }

        $suffix = $this->getSuffix();
        $suffix = (!empty($suffix)) ? '.'.$suffix : '.'.$this->getTLD();

        $name = \explode('.', \mb_substr($this->domain, 0, \mb_strlen($suffix) * -1));

        $this->name = \end($name);
        
        return $this->name;
    }

    /**
     * Returns sub-domain name
     * 
     * @return string
     */
    public function getSub(): string
    {
        $name = $this->getName();
        $name = (!empty($name)) ? '.'.$name : '';

        $suffix = $this->getSuffix();
        $suffix = (!empty($suffix)) ? '.'.$suffix : '.'.$this->getTLD();

        $domain = $name.$suffix;

        $sub = \explode('.', \mb_substr($this->domain, 0, \mb_strlen($domain) * -1));
        
        $this->sub = \implode('.', $sub);
        
        return $this->sub;
    }

    /**
     * Returns true if the public suffix is found;
     * 
     * @return bool
     */
    public function isKnown(): bool
    {
        if (\array_key_exists($this->getSuffix(), self::$list)) {
            return true;
        }

        return false;
    }

    /**
     * Returns true if the public suffix is found using ICANN domains section
     * 
     * @return bool
     */
    public function isICANN(): bool
    {
        if (isset(self::$list[$this->getSuffix()]) && self::$list[$this->getSuffix()]['type'] === 'ICANN') {
            return true;
        }

        return false;
    }

    /**
     * Returns true if the public suffix is found using PRIVATE domains section
     * 
     * @return bool
     */
    public function isPrivate(): bool
    {
        if (isset(self::$list[$this->getSuffix()]) && self::$list[$this->getSuffix()]['type'] === 'PRIVATE') {
            return true;
        }

        return false;
    }

    /**
     * Returns true if the public suffix is reserved for testing purpose
     * 
     * @return bool
     */
    public function isTest(): bool
    {
        if (\in_array($this->getTLD(), ['test', 'localhost'])) {
            return true;
        }

        return false;
    }
}