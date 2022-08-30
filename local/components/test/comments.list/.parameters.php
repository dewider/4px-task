<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule("iblock"))
	return;

$arIBlock=array();
$rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("TYPE" => $arCurrentValues["IBLOCK_TYPE"], "ACTIVE"=>"Y"));
while($arr=$rsIBlock->Fetch())
{
	$arIBlock[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];
}

$arComponentParameters = array(
    "PARAMETERS" => array(
        "IBLOCK_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("PAR_IBLOCK"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlock,
		),
        "ELEMENT_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("PAR_ELEMENT_ID"),
			"TYPE" => "STRING"
		),
        "COMMENTS_COUNT" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("PAR_COMMENTS_COUNT"),
			"TYPE" => "STRING",
            "DEFAULT" => 5
		),
        "SORT" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("PAR_SORT"),
			"TYPE" => "LIST",
			"VALUES" => [
                'ID_ASC' => GetMessage("ID_ASC"),
                'ID_DESC' => GetMessage("ID_DESC"),
                'DATE_ASC' => GetMessage("DATE_ASC"),
                'DATE_DESC' => GetMessage("DATE_DESC")
            ]
		),
    )
);
?>