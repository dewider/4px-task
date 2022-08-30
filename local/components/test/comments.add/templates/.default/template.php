<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);

$this->addExternalCss("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css");
?>
<div class="container-fluid">
    <form class="comment-add" action="" 
        data-component-name="<?=$this->getComponent()->getName()?>"
        data-component-signed="<?=$this->getComponent()->getSignedParameters()?>">
        <div class="row">
            <div class="col-md-5">
                <label class="comment-add__label" for="comment-input">Введите комментарий:</label>
                <textarea class="comment-add__input" name="comment-input" id="comment-input"></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 rating-stars">
                <?for($i = 1; $i <= 5; $i++ ):?>
                    <button class="rating-stars__item" date-rating="<?=$i?>">
                        <i class="fa-solid fa-star"></i>
                    </button>
                <?endfor?>
                <input name="comment-rating" type="hidden" class="rating-stars__input">
            </div>
            <div class="col-md-3 comment-add__submit">
                <input type="submit" value="Отправить">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <span class="comment-add__status"></span>
            </div>
        </div>
    </form>
</div>