<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-75">
    <div class="max-width">
        <ul class="breadcrumb">
            <li class="breadcrumb-item">
                <a title="<?= lang('Home'); ?>" href="/"><?= lang('Home'); ?></a>
            </li>
            <li class="breadcrumb-item">
                <a title="<?= lang('Info'); ?>" href="/info"><?= lang('Info'); ?></a>
            </li>
        </ul>
        <h1><?= $data['h1']; ?></h1>

        Сообщения на сайте форматируются с использованием варианта специального языка форматирования под названием <i>CommonMark</i> (который сам по себе является вариантом <i>Markdown</i>).
        <br><br>
        Если вы уже знакомы с <i>Markdown</i> (многие другие сайты тоже его используют), вы должны чувствовать себя как дома. Если нет, эта страница объяснит некоторые основы.
        <br><br>
        Самое важное в <i>Markdown</i> - абзацы (разделение пустыми строками), ставьте сколько хотите пустых строк (Enter), Markdown все равно оставит одну.
        <br><br>
        <table>
          <tbody><tr>
            <td width="125"><em>курсив</em></td>
            <td>окружить текст <tt>*звездочками*</tt></td>
          </tr>
          <tr>
            <td><strong>жирный</strong></td>
            <td>окружить текст <tt>**двумя звездочками**</tt></td>
          </tr>
          <tr>
            <td><strike>зачеркнутый</strike></td>
            <td>окружить текст <tt>~~двумя тильдами~~</tt></td>
          </tr>
          <tr>
            <td><tt>код (строка)</tt></td>
            <td>окружить текст <tt>`обратными ковычками`</tt></td>
          </tr>
          <tr>
            <td><a rel="nofollow noreferrer" href="http://example.com/">связаный текст</a></td>
            <td><tt>[связанный текст](http://example.com/)</tt> или просто URL-адрес для создания без заголовка</td>
          </tr>
          <tr>  
            <td><blockquote> цитата</blockquote></td>
            <td><tt>&gt;</tt> текс цитаты </td>
          </tr>
         </tbody></table>
        <br>
    </div>
</main>
<?php include TEMPLATE_DIR . '/_block/info-menu.php'; ?>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 