<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?= lang('Sign in'); ?></title>
        <link href="/assets/css/style.css" rel="stylesheet" type="text/css">
        <link rel="icon" href="/favicon.ico" type="image/png">
    </head>
    <body>
        <div class="wrap private">
            <div class="header">
                <div class="logo">
                    <a title="<?= lang('Home'); ?>" href="/"><?= lang('Home'); ?></a> 
                </div>
            </div>
            <br><br>
            <div class="login-nav-home"> 
                <form class="" action="/login" method="post">
                    <?php csrf_field(); ?>           
                    <div class="login-nav">
                        <label for="email">Email</label>
                        <input type="text" placeholder="<?= lang('Enter'); ?>  e-mail" name="email" id="email">
                    </div>
                    <div class="login-nav">
                        <label for="password"><?= lang('Password'); ?></label>
                        <input type="password" placeholder="<?= lang('Enter your password'); ?>" name="password" id="password">
                    </div>
                    <div class="login-nav">
                        <input type="checkbox" id="rememberme" name="rememberme" value="1">
                        <label id="rem-text" class="form-check-label" for="rememberme"><?= lang('Remember me'); ?></label>
                    </div>
                    <div class="login-nav">
                        <button type="submit" class="button-primary"><?= lang('Sign in'); ?></button>
                    </div>
                 </form>
                 <br>
            </div> 
        </div>
    </body>
</html>