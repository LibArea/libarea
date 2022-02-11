<?php

class Breadcrumbs
{
    // @var
    private $base;
    
    // @var null | string
    private $separator;
    
    // @var array
    private $links;

    // @param null | string $separator
    public function __construct(?string $separator = null)
    {
        $this->separator = $separator;
        $this->links = [];
    }

    // @return $this []
    public function base(string $baseUrl, string $title, ?string $icon = null): Breadcrumbs
    {
        $this->base = [
            'url'       => $baseUrl,
            'title'     => $title,
            'icon'      => $icon,
        ];
        return $this;
    }

    // @return Breadcrumbs
    public function addCrumb(string $title, ?string $url): Breadcrumbs
    {
        $this->links[] = $this->parts($title, $url);
        return $this;
    }

    // @return string
    public function render(?string $id = null): string
    {
        $init   = '<nav><ol id="' . $id .'">';
        $end    = '</ol></nav>';

        return $init . ($this->links ? $this->setBase() : '') . $this->collect($this->links) . $end;
    }

    public function allCrumbs(): ?array
    {
        return (!empty($this->links) ? $this->links : null);
    }

    // @return string
    private function collect(array $links): ?string
    {
        if (!$links) {
            return null;
        }
        $last = count($links) - 1;
        $breadcrumb = '';

        for ($b = 0; $b <= $last; $b++) {

            if ($b == $last) {
                $breadcrumb .= '<li class="active" aria-current="page">' . $this->separator . $links[$b]['title'] . '</li>';
            } else {
                $breadcrumb .= '<li>' . $this->separator . ' <a href="' . $links[$b]["url"] . '">' . $links[$b]['title'] . '</a></li>';
            }
        }

        return $breadcrumb;
    }

    // @return string
    private function setBase(): string
    {

        $title = $this->base["icon"] ?? $this->base['title'];
        if (!$this->links) {
            return '<li class="active" aria-current="page">' . $title . '</li>';
        }

        return '<li><a href="' . $this->base['url'] . '">' . $title . '</a></li>';
    }

    // @return array
    private function parts(string $title, string $url = null): array
    {
        return [
            'url'   => $this->setUrl($url),
            'title' => $title,
        ];
    }

    // @return string
    private function setUrl(string $url): string
    {
        $url = str_replace($this->base['url'], '', $url);
        $url = ($url[0] == '/' ? $url : '/' . $url);

        return $this->base['url'] . $url;
    }
}