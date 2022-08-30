<?

use Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (!Loader::includeModule('iblock')) {
    ShowError(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
    return;
}

class CommentsAddComponent extends CBitrixComponent
{
    public function __construct($component = null)
	{
		parent::__construct($component);
	}

    protected function listKeysSignedParameters()
    {
       return [
              'IBLOCK_ID',
              'ELEMENT_ID'
         ];
    }

    public function onPrepareComponentParams($params)
	{
		$params = parent::onPrepareComponentParams($params);
		return $params;
	}

    public function executeComponent()
    {
        $this->includeComponentTemplate();
    }
}
