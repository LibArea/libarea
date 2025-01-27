<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">
    <ShortName><?= config('meta', 'name'); ?></ShortName>
    <Description><?= config('meta', 'title'); ?></Description>
    <InputEncoding>utf-8</InputEncoding>
    <Image height="16" width="16" type="image/x-icon"><?= config('meta', 'url'); ?>/favicon.ico</Image>
    <Url type="text/html" template="<?= config('meta', 'url') . '/search/go?q={searchTerms}'; ?>" />
</OpenSearchDescription>