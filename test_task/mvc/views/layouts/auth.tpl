<div class="auth">
    <?php
    $is_auth = false;
    if($params && $params['user'] && !empty($params['user'])){
        $is_auth = true;
    }
    ?>
    <form id="frmDataAuth" action="<?php
        //Если авторизованы
        if($is_auth){
            print ('auth/logout');
        }else{
            print ('auth/login');
        }
    ?>" method="post">
        <input type="hidden" name="controller" value="auth" />
        <input type="hidden" name="page" value="<?php print(($is_auth)?'logout':'login'); ?>" />
        <div class="form-group">
            <label for="login">Имя пользователя:</label>
            <input type="text" class="form-control" id="login" name="login" placeholder="Имя пользователя"/>
        </div>
        <div class="form-group">
            <label for="pass">Пароль:</label>
            <input type="password" class="form-control" id="pass" name="pass" placeholder="Пароль"/>
        </div>
        <?php if($params && $params['user'] && !empty($params['user'])): ?>
        <div class="form-group">
            <input type="submit" value="Выйти" />
        </div>
        <?php else: ?>
        <div class="form-group">
            <input type="submit" value="Войти" />
        </div>
        <?php endif; ?>
    </form>
</div>