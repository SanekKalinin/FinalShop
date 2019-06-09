<?php include BASEPATH . '/app/views/layouts/header.php'; ?>

<section>
    <div class="container">
        <div class="row">

            <h3>Кабинет пользователя</h3>
            
            <h4>Привет, <?php echo $_SESSION['firstName'];?>!</h4>
            <ul>
                <li><a href="/cabinet/edit">Редактировать данные</a></li>
                <?php var_dump ($_SESSION);?>
                <!--<li><a href="/cabinet/history">Список покупок</a></li>-->
            </ul>
            
        </div>
    </div>
</section>

<?php include BASEPATH . '/app/views/layouts/footer.php'; ?>