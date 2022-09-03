<?
use Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc,
    \Bitrix\Main\Engine\Controller,
    \Bitrix\Main\Engine\ActionFilter;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (!Loader::includeModule('iblock')) die();

class CommentAddAjaxController extends Controller
{
    public function configureActions(){
        return [
            'add' => [
                'prefilters' => [
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ]
        ];
    }

    public function addAction(){

        $params = $this->getUnsignedParameters();
        $commentText = htmlspecialchars($_REQUEST['comment-input']);
        $commentRating = intval($_REQUEST['comment-rating']);

        if(empty($commentText)) return [
            'message' => Loc::getMessage('EMPTY'),
        ];

        $newComment = new CIBlockElement;
        $id = $newComment->Add([
            'IBLOCK_ID' => $params['IBLOCK_ID'],
            'ACTIVE' => ($GLOBALS['USER']->IsAuthorized()) ? 'Y' : 'N',
            'NAME' => "Комментарий от ".date("Y-m-d H:i:s") ,
            'DETAIL_TEXT' => $commentText,
            'PROPERTY_VALUES' => [
                'RATING' => $commentRating,
                'ELEMENT_ID' => $params['ELEMENT_ID'],
                'AUTHOR' => $GLOBALS['USER']->GetID()
            ]
        ]);

        return [
            'message' => ($id === false) ? $newComment->LAST_ERROR : Loc::getMessage('SUCCESS'),
        ];
    }
}