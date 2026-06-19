<?php

class LinkCard
{
    private string $url;
    private string $title;
    private string $description;
    private string $domain;
    private string $favicon;

    public function __construct(string $url, string $title, string $description = '')
    {
        $this->url = $url;
        $this->title = $title;
        $this->description = $description;
        $this->domain = $this->extractDomain($url);
        $this->favicon = 'https://www.google.com/s2/favicons?domain=' . $this->domain;
    }

    private function extractDomain(string $url): string
    {
        $parsed = parse_url($url);
        return $parsed['host'] ?? '';
    }

    public function render(): string
    {
        $escapedUrl = htmlspecialchars($this->url, ENT_QUOTES, 'UTF-8');
        $escapedTitle = htmlspecialchars($this->title, ENT_QUOTES, 'UTF-8');
        $escapedDesc = htmlspecialchars($this->description, ENT_QUOTES, 'UTF-8');
        $escapedDomain = htmlspecialchars($this->domain, ENT_QUOTES, 'UTF-8');
        $escapedFavicon = htmlspecialchars($this->favicon, ENT_QUOTES, 'UTF-8');

        $html = <<<HTML
<div class="link-card">
    <a href="{$escapedUrl}" target="_blank" rel="noopener noreferrer">
        <div class="link-card-content">
            <div class="link-card-title">{$escapedTitle}</div>
            <div class="link-card-description">{$escapedDesc}</div>
            <div class="link-card-domain">
                <img src="{$escapedFavicon}" alt="favicon" width="16" height="16">
                <span>{$escapedDomain}</span>
            </div>
        </div>
    </a>
</div>
HTML;

        return $html;
    }

    public static function createDefault(): self
    {
        return new self(
            'https://webs-igysports.com',
            '爱游戏体育 - 官方网站',
            '爱游戏体育为您提供最新体育资讯和赛事信息'
        );
    }

    public static function createWithCustom(string $url, string $title, string $description = ''): self
    {
        return new self($url, $title, $description);
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getFavicon(): string
    {
        return $this->favicon;
    }

    public function toArray(): array
    {
        return [
            'url' => $this->url,
            'title' => $this->title,
            'description' => $this->description,
            'domain' => $this->domain,
            'favicon' => $this->favicon,
        ];
    }
}

function renderLinkCard(string $url, string $title, string $description = ''): string
{
    $card = new LinkCard($url, $title, $description);
    return $card->render();
}

function renderMultipleLinkCards(array $cards): string
{
    $html = '<div class="link-cards-container">';
    foreach ($cards as $card) {
        if ($card instanceof LinkCard) {
            $html .= $card->render();
        } elseif (is_array($card) && isset($card['url'], $card['title'])) {
            $instance = new LinkCard(
                $card['url'],
                $card['title'],
                $card['description'] ?? ''
            );
            $html .= $instance->render();
        }
    }
    $html .= '</div>';
    return $html;
}

$defaultCard = LinkCard::createDefault();
echo $defaultCard->render();

$customCard = LinkCard::createWithCustom(
    'https://webs-igysports.com',
    '爱游戏体育',
    '专注体育赛事，尽在爱游戏体育'
);
echo $customCard->render();

$cards = [
    new LinkCard('https://webs-igysports.com', '爱游戏体育', '平台首页'),
    new LinkCard('https://webs-igysports.com/news', '爱游戏体育新闻'),
];
echo renderMultipleLinkCards($cards);