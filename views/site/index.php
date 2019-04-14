<?php include ROOT . '/views/layouts/header.php' ?>

<div class="container">
    <?php if (!$yaAuthorization): ?>
        <div class="ya-auto-block">
            <a class="ya-auto" href="https://oauth.yandex.ru/authorize?response_type=code&client_id=<?= $client_id ?>">Авторизация</a>
        </div>
    <?php else: ?>
        <input type="hidden" id="form-sort-page" value="<?= $yaDiskFileLimit ?>"/>
        <input type="hidden" id="form-sort-name" value=""/>
        <input type="hidden" id="form-sort-rateit" value=""/>
        <div class="clearfix text-right user-block">
            <div><i class="fas fa-user"></i><?= $firstName ?> <?= $lastName ?></div>
            <div><i class="fas fa-at"></i><?= $email ?></div>
            <div><a href="/exit/"><i class="fas fa-door-closed"></i>Выйти</a></div>
        </div>
        <h1>Галерея изображений Яндекс.Диск</h1>
        <div class="sort-block">Сортировка:
            <a href="#" id="link-sort-name" data-sort-name="name">по наимемнованию<i class="fas"></i></a>
            <a href="#" id="link-sort-rateit"
               data-sort-rateit="desc" class="<?= ($yaDiskFileRateit ? 'rateit-display' : 'rateit-display-none'); ?>">по рейтингу<i class="fas"></i></a>
        </div>
        <?php if (isset($yaDiskFile) && is_array($yaDiskFile)): ?>
            <div class="demo-gallery">
                <ul id="lightgallery" class="list-unstyled row">
                    <?php foreach ($yaDiskFile as $value): ?>
                        <li class="col-xs-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 col-xxl-2 col-xxxl-2 col-xxxxl-2"
                            data-id="<?= $value['md5'] ?>" data-src="<?= $value['file']; ?>">
                        <span class="flex-image-card">
                            <span class="flex-image-conteiner">
                                <a href=""><img class="img-responsive" src="<?= $value['preview']; ?>"
                                                alt="<?= $value['name'] ?>"/></a>
                            </span>
                        </span>
                            <div class="rateit-container">
                                <div class="rateit" data-productid="<?= $value['md5'] ?>"
                                     data-rateit-readonly="<?= ($value['rateit'] > 0) ? true : false; ?>"
                                     data-rateit-min="0" data-rateit-max="5"
                                     data-rateit-value="<?=$value['rateit']?>"
                                     data-rateit-step="1"></div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <nav>
                <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="#" data-page="10">10</a></li>
                    <li class="page-item"><a class="page-link" href="#" data-page="20">20</a></li>
                    <li class="page-item"><a class="page-link" href="#" data-page="50">50</a></li>
                    <li class="page-item"><a class="page-link" href="#" data-page="0">Все</a></li>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include ROOT . '/views/layouts/footer.php' ?>
