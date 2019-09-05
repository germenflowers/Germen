$(function () {
    $(document).on("reCalcTotal", function () {
        var discountPercent = Number($(".js-discount-percent").val());

        var $selectedUpSaleProducts = $(".js-upsale-products")
        if (!isJson($selectedUpSaleProducts.val())) {
            return false;
        }

        var products = JSON.parse($selectedUpSaleProducts.val())

        var upSaleSum = 0;

        for (var id in products) {
            var quantity = products[id]
            var price = Number($("[data-id=" + id + "]").data("price"))
            upSaleSum += (price * quantity);
        }

        $(".js-upsale-sum").text(formatPrice(upSaleSum))

        var $selectedMainProducts = $(".js-main-products")
        if (!isJson($selectedMainProducts.val())) {
            return false;
        }

        var mainProducts = JSON.parse($selectedMainProducts.val())
        var mainBlock = $(".js-main-basket-item.js-basket-item-info");

        var mainPrice = mainBlock.data("price")
        var mainId = mainBlock.data("id")

        if (!mainProducts.hasOwnProperty(mainId)) {
            return false;
        }

        var mainSum = mainPrice * mainProducts[mainId]
        $(".js-main-sum").text(formatPrice(discountPrice(mainSum, discountPercent)));
        showOldPrice($(".js-main-sum"), mainSum)
        var totalSum = Number(mainSum) + Number(upSaleSum)
        var discountTotalSum = discountPrice(Number(mainSum), discountPercent) + Number(upSaleSum)

        $(".js-total-sum").text(formatPrice(discountTotalSum))

        showOldPrice($(".js-total-sum"), totalSum)

        $(".js-discount-diff").text(formatPrice(totalSum - discountTotalSum))

        $(".js-basket-item").not(".js-basket-item-base").each(function (idx, elem) {
            if (!$(elem).find(".js-main-basket-item").length) {
                return true
            }

            var price = $(elem).find(".js-basket-item-info").data("price")
            $(elem).find(".js-price").text(formatPrice(discountPrice(price, discountPercent)))
            if (discountPercent > 0) {
                showOldPrice($(elem).find(".js-price"), price)
            }
        })

        if (discountPercent <= 0) {
            $(".js-old-price").remove()
        }

    })

    $(document).on("click", ".js-add-basket", function (event) {
        event.preventDefault();

        var productType, $container, $block
        if (($block = $(this).closest(".js-upsale-block")).length) {
            productType = "upsale"
            $container = $block
        }

        if (($block = $(this).closest(".js-bookmate-block")).length) {
            productType = "bookmate"
            $container = $block
        }

        if (!productType) {
            return;
        }

        var newBasketItem = {}

        newBasketItem.name = $container.find(".js-name").text()
        newBasketItem.id = $container.data("id")

        if ($container.find(".js-image-block img").length) {
            newBasketItem.imageUrl = $container.find(".js-image-block img").attr("src")
        }


        newBasketItem.price = Number($container.find(".js-price").text())


        if (Number(newBasketItem.id) <= 0) {
            return;
        }

        var $selectedUpSaleProducts = $(".js-upsale-products")
        if (!isJson($selectedUpSaleProducts.val())) {
            $selectedUpSaleProducts.val(JSON.stringify({}))
        }

        var products = JSON.parse($selectedUpSaleProducts.val())

        if (products.hasOwnProperty(newBasketItem.id)) {
            if (newBasketItem.price <= 0) {
                return;
            }
            products[newBasketItem.id]++;
            $selectedUpSaleProducts.val(JSON.stringify(products))

            var $basketItems = $(".js-basket-item [data-id]")
            $basketItems.each(function (idx, elem) {
                if (Number($(elem).data("id")) === Number(newBasketItem.id)) {
                    $(elem).find(".js-quantity-field").val(products[newBasketItem.id])
                    return false;
                }
            });

            $(document).trigger("reCalcTotal");

            return;
        }

        products[newBasketItem.id] = 1;
        if (newBasketItem.price <= 0) {
            $(".js-upsale-products-block").find("[data-id=" + newBasketItem.id + "]").find(".js-add-basket").hide();
        }

        if (productType === "bookmate") {
            $(".js-bookmate-block").find(".js-add-basket").hide();
        }

        $selectedUpSaleProducts.val(JSON.stringify(products))
        $(document).trigger("reCalcTotal");

        var $basketItemBase = $(".js-basket-item-base");

        var $newBasketItem = $basketItemBase.clone()

        $newBasketItem.removeClass("js-basket-item-base")
        $newBasketItem.show()

        $newBasketItem.find(".js-name").text(newBasketItem.name)
        if (productType === "bookmate" || Number(newBasketItem.price) <= 0) {
            $newBasketItem.find(".js-price-block").remove()
            $newBasketItem.find(".js-quantity-control").remove()
        } else {
            $newBasketItem.find(".js-price").text(formatPrice(newBasketItem.price))
            $newBasketItem.find(".js-detail").data("price", newBasketItem.price)
        }

        if (!newBasketItem.imageUrl || newBasketItem.imageUrl.length <= 0) {
            $newBasketItem.find(".js-image-block").remove()
        } else {
            $newBasketItem.find(".js-image-block img").attr("src", newBasketItem.imageUrl)
            $newBasketItem.find(".js-image-block img").attr("alt", newBasketItem.name)
        }

        $newBasketItem.find(".js-basket-item-info").data("id", newBasketItem.id)
        $newBasketItem.find(".js-basket-item-info").data("price", Number(newBasketItem.price))

        $newBasketItem.find(".js-basket-item-info").removeClass("js-detail")
        $(".js-basket-item").last().after($newBasketItem)
        $(document).trigger("reCalcTotal");

    })


    $(document).on("click", ".js-remove-basket-item", function (event) {
        event.preventDefault();

        if ($(this).closest(".js-main-basket-item").length) {
            window.location.href = "/"
        } else {
            var $upSaleProductBlock = $(".js-upsale-products-block")
            var $container = $(this).closest(".js-basket-item")
            var $info = $container.find(".js-basket-item-info")
            var id = Number($info.data("id"))
            if (Number($info.data("price")) <= 0 && id > 0) {
                $upSaleProductBlock.find("[data-id=" + id + "]").find(".js-add-basket").show();
            }

            if ($upSaleProductBlock.find("[data-id=" + id + "]").hasClass("js-bookmate-block")) {
                $(".js-bookmate-block").find(".js-add-basket").show();
            }

            $(this).closest(".js-basket-item").remove()

            var $selectedUpSaleProducts = $(".js-upsale-products")
            if (!isJson($selectedUpSaleProducts.val())) {
                $selectedUpSaleProducts.val(JSON.stringify({}))
            }

            var products = JSON.parse($selectedUpSaleProducts.val())

            if (products.hasOwnProperty(id)) {
                delete products[id];
                $selectedUpSaleProducts.val(JSON.stringify(products))
                $(document).trigger("reCalcTotal");
            }
        }
    })

    $(document).on('click', '.js-order-bookmate', function(){
        event.preventDefault();
        var $orderBookmateOffers = $('.js-order-bookmate-offers');

        $orderBookmateOffers
            .removeClass('hidden');

        setTimeout(function(){
            $orderBookmateOffers
                .trigger('swiper:update');
        }, 0);
    });


    $(document).on("click", ".js-show-coupon-form", function (event) {
        event.preventDefault();
        $(".js-coupon-form").slideToggle();
    })


    $(document).on('click', '.js-coupon-apply', function (event) {
        event.preventDefault();
        var btn = $(this);
        var field = $(".js-coupon-field");
        var loadingClass = 'btn--loading';

        btn.removeClass('error');

        if (field.val().length <= 0) {
            field.addClass('error');
            return;
        }

        field.removeClass('error');

        btn.prop('disabled', true);
        btn.addClass(loadingClass);

        $.ajax(
            window.location.href,
            {
                dataType: "json",
                data: {
                    coupon: field.val()
                },
                success: function (result) {
                    afterEnterCoupon(result);
                    btn.prop('disabled', false);
                    btn.removeClass(loadingClass);

                },
                type: "POST",
            }
        )
    });

    function afterEnterCoupon(result) {
        if (result.STATUS_ENTER === 'BAD') {
            $('.js-coupon-error').show();
            $(".js-discount-percent").val(0)
        } else {
            if (result.COUPON) {
                $(".js-coupon-name").text(result.DISCOUNT_NAME)
                $(".js-coupon-value").text(result.COUPON)
                $(".js-coupon-form").hide();
                $(".js-show-coupon-form").hide();
                $(".js-coupon-applied").show();
                $(".js-coupon-field").val("")
                $(".js-coupon-error").hide()
                $(".js-discount-percent").val(result.PERCENT)
            } else {
                $(".js-show-coupon-form").show();
                $(".js-coupon-applied").hide();
                $(".js-discount-percent").val(0)
            }
        }

        $(document).trigger("reCalcTotal");
    }

    $(document).on('click', '.js-coupon-cancel', function (event) {
        event.preventDefault()
        $.post(window.location.href, {coupon: ''}, function (result) {
            afterEnterCoupon(result);
        }, 'json');

        return false;
    });

    $(document).on("click", ".js-minus", function (event) {
        event.preventDefault();
        var $container = $(this).closest(".js-basket-item-info")
        var id = $container.data("id")
        var currentQuantity = $container.find(".js-quantity-field").val();

        var $currentSelectedProducts
        if ($(this).closest(".js-main-basket-item").length) {
            $currentSelectedProducts = $(".js-main-products")
        } else {
            $currentSelectedProducts = $(".js-upsale-products")
        }

        if (!isJson($currentSelectedProducts.val())) {
            $currentSelectedProducts.val(JSON.stringify({}))
        }

        var products = JSON.parse($currentSelectedProducts.val())

        if (!products.hasOwnProperty(id)) {
            return false;
        }


        if (currentQuantity <= 1) {
            if ($(this).closest(".js-main-basket-item").length) {
                window.location.href = "/"
            } else {
                delete products[id];
                $(this).closest(".js-basket-item").remove()
            }
        } else {
            products[id]--;
            $container.find(".js-quantity-field").val(products[id])
        }

        $currentSelectedProducts.val(JSON.stringify(products))
        $(document).trigger("reCalcTotal");
    })

    $(document).on("click", ".js-plus", function (event) {
        event.preventDefault();
        var $container = $(this).closest(".js-basket-item-info")
        var id = $container.data("id")
        var $currentSelectedProducts
        if ($(this).closest(".js-main-basket-item").length) {
            $currentSelectedProducts = $(".js-main-products")
        } else {
            $currentSelectedProducts = $(".js-upsale-products")
        }
        if (!isJson($currentSelectedProducts.val())) {
            $currentSelectedProducts.val(JSON.stringify({}))
        }

        var products = JSON.parse($currentSelectedProducts.val())

        if (!products.hasOwnProperty(id)) {
            return false;
        }

        products[id]++;
        $container.find(".js-quantity-field").val(products[id])

        $currentSelectedProducts.val(JSON.stringify(products))
        $(document).trigger("reCalcTotal");
    })

    $(document).on("change", ".js-quantity-field", function (event) {
        event.preventDefault();
        var $container = $(this).closest(".js-basket-item-info")
        var id = $container.data("id")
        var $currentSelectedProducts
        if ($(this).closest(".js-main-basket-item").length) {
            $currentSelectedProducts = $(".js-main-products")
        } else {
            $currentSelectedProducts = $(".js-upsale-products")
        }

        if (!isJson($currentSelectedProducts.val())) {
            $currentSelectedProducts.val(JSON.stringify({}))
        }

        var products = JSON.parse($currentSelectedProducts.val())

        if (!products.hasOwnProperty(id)) {
            return false;
        }

        var newQuantity = $(this).val()

        if (isNaN(Number(newQuantity)) || Number(newQuantity) <= 0) {
            newQuantity = 1
            $(this).val(newQuantity)
        }

        products[id] = newQuantity

        $currentSelectedProducts.val(JSON.stringify(products))
        $(document).trigger("reCalcTotal");
    })


    function isJson(string) {
        try {
            JSON.parse(string);
            return true;
        } catch (e) {
            return false;
        }
    }

    function formatPrice(price) {
        price = String(Math.round(price));
        return price.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1 ');
    }

    function discountPrice(price, percent) {
        if (price <= 0 || percent <= 0) {
            return price;
        }
        return price - ((price * percent) / 100)
    }

    function showOldPrice($priceElem, price) {
        if ($priceElem.siblings(".js-old-price").length > 0) {
            $priceElem.siblings(".js-old-price").find(".js-old-price-value").text(formatPrice(price))
            return;
        }
        $priceElem.before('<span class="js-old-price promo-order__submit__old-price"><span class="js-old-price-value">' + formatPrice(price) + '</span><span class="rouble"></span></span>')
    }

    $(document).trigger("reCalcTotal")
})