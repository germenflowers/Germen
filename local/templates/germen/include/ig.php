<?
$posts = \PDV\Tools::getInstagramPosts();
if ( !empty($posts) ):
?>
    <div class="promo-instagram__wrapper">
        <div class="content__container">
            <div class="head-h2 promo-instagram__title">Инстаграм <a href="https://www.instagram.com/<?=$posts['username']?>/" target="_blank"> @<?=$posts['username']?></a></div>
        </div>

        <div class="promo-instagram__block__wrapper">
            <?foreach ( $posts['ITEMS'] as $item ): ?>
                <div class="promo-instagram__block">
                    <a href="<?=$item['LINK']?>" target="_blank">
                        <img src="<?=$item['IMAGE']?>" class="promo-instagram__img" alt="">
                    </a>
                </div>
            <?endforeach;?>
        </div>
    </div>
<?endif;?>