<?
use \Bitrix\Main\Loader,
    \Bitrix\Main\Localization\Loc,
    \Bitrix\Iblock\Iblock,
    \Bitrix\Main\UI\PageNavigation,
    \Bitrix\Main\Engine\Contract\Controllerable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (!Loader::includeModule('iblock')) {
    ShowError(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
    return;
}

class CommentsListComponent extends CBitrixComponent implements Controllerable
{
    public function __construct($component = null)
    {
        parent::__construct($component);
    }

    public function onPrepareComponentParams($params)
    {
        $params = parent::onPrepareComponentParams($params);
        $params['SORT'] = (isset($params['SORT'])) ? $params['SORT'] : 'ID_ASC';
        $params['COMMENTS_COUNT'] = (isset($params['COMMENTS_COUNT'])) ? intval($params['COMMENTS_COUNT']) : 5;
        return $params;
    }

    public function configureActions()
    {
        return [];
    }

    protected function listKeysSignedParameters(){
        return [
            'IBLOCK_ID', 'ELEMENT_ID'
        ];
    }

    public function getAction(){
        if(intval($_REQUEST['perPage']) > 0) 
            $this->arParams['COMMENTS_COUNT'] = intval($_REQUEST['perPage']);
        $this->arParams['SORT'] = htmlspecialchars($_REQUEST['sort']);

        ob_start();
        $this->executeComponent();
        $res = ob_get_contents();
        ob_end_clean();

        return $res;
    }

    protected function getItems()
    {
        if(empty($this->arParams['IBLOCK_ID']) || empty($this->arParams['ELEMENT_ID'])) return;

        $iblockClass = Iblock::wakeUp($this->arParams['IBLOCK_ID'])->getEntityDataClass();

        if (!$iblockClass) {
            ShowError(Loc::getMessage('SET_API_CODE'));
            return;
        }

        switch($this->arParams['SORT']){
            case 'ID_DESC':
                $sortField = 'ID';
                $sortDirect = 'desc';
                break;
            case 'DATE_ASC':
                $sortField = 'TIMESTAMP_X';
                $sortDirect = 'asc';
                break;
            case 'DATE_DESC':
                $sortField = 'TIMESTAMP_X';
                $sortDirect = 'desc';
                break;
            default:
                $sortField = 'ID';
                $sortDirect = 'asc';
        }

        $dbItems = $iblockClass::getList([
			'select' => [
                'ID','NAME','IBLOCK_ID','DETAIL_TEXT','TIMESTAMP_X','PREVIEW_TEXT',
                'AUTHOR_' => 'AUTHOR',
                'RATING_' => 'RATING',
                'USERNAME_' =>'USERNAME'
            ],
			'filter' => [
				'ACTIVE' => 'Y',
				'ELEMENT_ID.VALUE' => $this->arParams['ELEMENT_ID']
			],
            'order' => [
                $sortField => $sortDirect
            ],
            'count_total' => true,
            'offset' => $this->arResult['NAV']->getOffset(),
            'limit' => $this->arResult['NAV']->getLimit(),
            'runtime' => [
                'USERNAME' => [
                    'data_type' => '\Bitrix\Main\UserTable',
                    'reference' => array('=this.AUTHOR_VALUE' => 'ref.ID')
                ]
            ],
			'cache' => [
				'ttl' => 3600
			]
		]);
        $this->arResult['NAV']->setRecordCount($dbItems->getCount());
        $this->arResult['ITEMS'] = [];
        while($arItem = $dbItems->fetch()){
			array_push($this->arResult['ITEMS'], [
                'ID' => $arItem['ID'],
                'RATING' => intval($arItem['RATING_VALUE']),
                'TEXT' => $arItem['DETAIL_TEXT'],
                'DATETIME' => $arItem['TIMESTAMP_X']->toString(),
                'ANSWER' => $arItem['PREVIEW_TEXT'],
                'AUTHOR' => trim(
                    $arItem['USERNAME_SECOND_NAME'].' '
                    .$arItem['USERNAME_NAME'].' '
                    .$arItem['USERNAME_LAST_NAME']
                )
            ]);
		}
    }

    public function executeComponent()
    {
        global $APPLICATION, $USER;
        
        $this->arResult['NAV'] = new PageNavigation("nav-comments");
        $this->arResult['NAV']->setPageSize($this->arParams['COMMENTS_COUNT']);
        if(isset($_REQUEST['page']) && intval($_REQUEST['page']) > 0 ){
            $this->arResult['NAV']->setCurrentPage(intval($_REQUEST['page']));
        } else {
            $this->arResult['NAV']->initFromUri();
        }

        $CACHE_ID = SITE_ID . "|" . $APPLICATION->GetCurPage() . "|";
		foreach ($this->arParams as $k => $v)
			if (strncmp("~", $k, 1))
				$CACHE_ID .= "," . $k . "=" . $v;
		$CACHE_ID .= "|" . $USER->GetGroups()
			. "|page=" . $this->arResult['NAV']->getCurrentPage();

        if ($this->StartResultCache()) {
            $this->getItems();
            $this->includeComponentTemplate();
        }
    }
}
