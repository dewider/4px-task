<?

use \Bitrix\Main\Loader,
	\Bitrix\Main\Localization\Loc,
	\Bitrix\Iblock\Iblock;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (!Loader::includeModule('iblock')) {
	ShowError(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
	return;
}

class CommentsRatingComponent extends CBitrixComponent
{
	public function __construct($component = null)
	{
		parent::__construct($component);
	}

	public function onPrepareComponentParams($params)
	{
		$params = parent::onPrepareComponentParams($params);
		return $params;
	}

	protected function getRating()
	{
		if(empty($this->arParams['IBLOCK_ID']) || empty($this->arParams['ELEMENT_ID'])) return;

		$iblockClass = Iblock::wakeUp($this->arParams['IBLOCK_ID'])->getEntityDataClass();
		if (!$iblockClass) {
            ShowError(Loc::getMessage('SET_API_CODE'));
            return;
        }

		$dbItems = $iblockClass::getList([
			'select' => ['ID', 'NAME', 'IBLOCK_ID', 'RATING_' => 'RATING'],
			'filter' => [
				'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
				'ACTIVE' => 'Y',
				'>RATING.VALUE' => 0,
				'ELEMENT_ID.VALUE' => $this->arParams['ELEMENT_ID']
			],
			'cache' => [
				'ttl' => 3600
			]
		]);
		$total = $count = 0;
		while($arItem = $dbItems->fetch()){
			$total += intval($arItem['RATING_VALUE']);
			$count++;
		}
		$this->arResult['VALUE'] = $total / $count;

	}

	public function executeComponent()
	{
		if ($this->StartResultCache())
		{
			$this->getRating();
		}
		$this->includeComponentTemplate();
	}
}
