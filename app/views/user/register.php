<?php include BASEPATH . '/app/views/layouts/header.php'; ?>

<section>
    <div class="container">
        <div class="row">

            <div class="col-sm-4 col-sm-offset-4 padding-right">
                
                <?php if ($result): ?>
                    <p>Вы зарегистрированы!</p>
                <?php else: ?>
                    <?php if (isset($errors) && is_array($errors)): ?>
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li> - <?php echo $error;?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <div class="signup-form"><!--sign up form-->
                        <h2>Регистрация на сайте</h2>
                        <form action="#" method="post">
                            <input type="text" name="firstName" placeholder="Имя" value="<?php echo $firstName; ?>"/>
                            <input type="text" name="lastName" placeholder="Фамилия" value="<?php echo $lastName; ?>"/>
                            <input type="text" name="userName" placeholder="Имя пользователя" value="<?php echo $userName; ?>"/>
                            <input type="email" name="email" placeholder="E-mail" value="<?php echo $email; ?>"/>
                            <input type="password" name="password" placeholder="Пароль" value="<?php echo $password; ?>"/>
                            <input type="submit" name="submit" class="btn btn-default" value="Регистрация" />
                        </form>
                    </div><!--/sign up form-->
                
                <?php endif; ?>
                <br/>
                <br/>
            </div>
        </div>
    </div>
</section>

<?php include BASEPATH . '/app/views/layouts/footer.php'; ?>