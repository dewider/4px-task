<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);

$this->addExternalCss("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css");
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h3>Рейтинг</h3>
        </div>
    </div>
    <div class="row comments-rating">
        <div class="col-md-12 rating-stars">
            <? for($i=1; $i <= 5; $i++):?>
                <div class="rating-stars__item<?= ($i <= $arResult["VALUE"]) ? " rating-stars__item_selected" : ''?>">
                    <i class="fa-solid fa-star"></i>
                </div>
            <?endfor;?>
        </div>
    </div>
</div>