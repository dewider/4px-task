<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

$this->addExternalCss("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css");
?>

<div class="container-fluid">
    <div class="comments-wrap" 
        data-component-name="<?= $this->getComponent()->getName() ?>"
        data-signed-params="<?= $this->getComponent()->getSignedParameters() ?>"
        data-per-page="<?= $arParams["COMMENTS_COUNT"] ?>"
        data-sort="<?= $arParams["SORT"] ?>"
        data-user-auth="<?=$USER->IsAuthorized() ? 'true' : 'false'?>">
        <div class="row">
            <div class="col-md-12">
                <h2>Отзывы</h2>
            </div>
        </div>
        <? foreach ($arResult['ITEMS'] as $item) : ?>
            <div class="comment">
                <div class="row comment__head">
                    <div class="col-md-5 comment__name">
                        <h3><?= $item['AUTHOR'] ?></h3>
                    </div>
                    <div class="col-md-7">
                        <?= $item['DATETIME'] ?>
                    </div>
                </div>
                <div class="row comment__rating">
                    <div class="col-md-12 rating-stars">
                        <? for ($i = 1; $i <= 5; $i++) : ?>
                            <div class="rating-stars__item<?= ($i <= $item['RATING']) ? " rating-stars__item_selected" : '' ?>">
                                <i class="fa-solid fa-star"></i>
                            </div>
                        <? endfor; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $item['TEXT'] ?>
                    </div>
                </div>
                <? if (!empty($item['ANSWER'])) : ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="comment__answer-title">
                                <h3>Ответ:</h3>
                            </div>
                            <div class="comment__answer">
                                <?= $item['ANSWER'] ?>
                            </div>
                        </div>
                    </div>
                <? endif; ?>
            </div>
        <? endforeach; ?>
        <?
        $APPLICATION->IncludeComponent(
            "bitrix:main.pagenavigation",
            "",
            array(
                "NAV_OBJECT" => $arResult['NAV'],
                "SEF_MODE" => "N",
            ),
            $component
        );
        ?>
    </div>
</div>