<?php

/**
 * Class UsersListComponent
 */
class UsersListComponent extends \CBitrixComponent
{
    /**
     * @return mixed|void
     */
    public function executeComponent()
    {
        /** @global \CMain $APPLICATION */
        global $APPLICATION;

        $APPLICATION->RestartBuffer();
        $this->arResult = $this->getUsersList();
        $this->includeComponentTemplate();
    }

    /**
     * @return array
     *Resumes the result of the selection of the properties of the information block elements.
     * Вовзращает результат выборки свойств элементов инфоблока
     */
    protected function getUsersList()
    {
        if (CModule::IncludeModule('iblock')) {
            /*
             * Добавление типа ИБ
             */
            $iblocktype = "Simple"; // Уникальный тип блока на en!!!
            $obIBlockType = new CIBlockType;
            $arFields = Array(
                "ID" => $iblocktype,
                "SECTIONS" => "Y",
                "LANG" => Array(
                    "ru" => Array(
                        "NAME" => "Пользователи",
                    )
                )
            );
            $res = $obIBlockType->Add($arFields);

            /*
             * Добавление ИБ
             */

            $ib = new CIBlock;
            $IBLOCK_TYPE = "$iblocktype"; //тип инфоблока
            $SITE_ID = "s1"; //сайт
// Настройка доступа
            $arAccess = array(
                "2" => "R", // Все пользователи
            );
            if ($contentGroupId) {
                $arAccess[$contentGroupId] = "X";
            } // Полный доступ
            if ($editorGroupId) {
                $arAccess[$editorGroupId] = "W";
            } // Запись
            if ($ownerGroupId) {
                $arAccess[$ownerGroupId] = "X";
            } // Полный доступ

            $arFields = Array(
                "ACTIVE" => "Y",
                "NAME" => "Пользователи",
                "CODE" => "tm",
                "IBLOCK_TYPE_ID" => $IBLOCK_TYPE,
                "SITE_ID" => $SITE_ID,
                "SORT" => "5",
                "GROUP_ID" => $arAccess, // Права доступа
            );
//
            $ID = $ib->Add($arFields);
            if ($ID > 0) {
                echo "инфоблок успешно создан";
            } else {
                echo "ошибка создания инфоблока";
                return false;
            }

        }
        CModule::IncludeModule("iblock");
        $iblocks = GetIBlockList("Simple");
        while($arIBlock = $iblocks->GetNext())
        {
            return "Название: ".$arIBlock["ID"];
        }
    }
}



