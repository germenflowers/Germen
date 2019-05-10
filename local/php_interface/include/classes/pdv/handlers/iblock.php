<?

namespace PDV\Handlers;

class IBlock
{
    public function checkUpSaleImage($arFields)
    {
        if ((int)$arFields["IBLOCK_ID"] !== IBLOCK_ID__UPSALE) {
            return true;
        }

        global $APPLICATION;
        if (empty($arFields["PREVIEW_PICTURE"]["tmp_name"])) {
            return true;
        }

        $arFileSizes = \CFile::GetImageSize($arFields["PREVIEW_PICTURE"]["tmp_name"]);

        if (!is_array($arFileSizes)) {
            return true;
        }

        $width = !empty($arFileSizes[0]) ? $arFileSizes[0] : 0;

        if ($width < 145) {
            $APPLICATION->ThrowException("Ширина изображения должна быть не менее 145");
            return false;
        }
    }
}