function $(selector) {
    return document.querySelector(selector);
}

function $$(selector) {
    return document.querySelectorAll(selector);
}

const brandDanhmuc = $$(".category-item-band");
const Danhmuc_active = $(".category-item--active");

brandDanhmuc.forEach(function (brand) {
    brand.addEventListener('click', function () {
        const brandValue = brand.getAttribute('data-id_thuonghieu');
        brandDanhmuc.forEach(function (otherBrand) {
            otherBrand.classList.remove('check_active');
        });
        brand.classList.toggle('check_active');
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('thuonghieu', brandValue);
        window.location.href = currentUrl.toString();
    });
});




// ____________________________________________________________________________Slide chuyển động_________________________________________________________________________________________//

const imgPosition = $$('.slide_header-169 img');
const imgContainer = $('.slide_header-169');
const dotItem = $$('.slider_header-dot');
if (imgPosition && imgContainer && dotItem) {
    let index = 0;
    let interval;

    function startSlider() {
        interval = setInterval(imgSlider, 3000);
    }

    function imgSlider() {
        index++;
        if (index >= imgPosition.length) index = 0;
        slide(index);
    }

    function slide(index) {
        imgContainer.style.left = "-" + index * 100 + "%";
        const dotActive = $$('.dot_active');
        dotActive.forEach(function (dot) {
            dot.classList.remove('dot_active');
        });
        dotItem[index].classList.add('dot_active');
    }

    imgPosition.forEach(function (image, index) {
        image.style.left = index * 100 + '%';
    });

    dotItem.forEach(function (dot, i) {
        dot.addEventListener('click', function () {
            index = i;
            slide(index);
            clearInterval(interval);
            startSlider();
        });
    });

    startSlider();
}




// ____________________________________________________________________________________Click yêu thích_______________________________________________________________________//

$$('.product-item__like').forEach(item => {
    item.addEventListener('click', function (event) {
        event.preventDefault();
        item.classList.toggle('product-item__like--liked');
    });
});




// ______________________________________________________________________________Click sắp xếp sp ______________________________________________________________________________//

const btnfillters = $$('.home-filter button');
btnfillters.forEach(function (btnfillter,index) {
    btnfillter.addEventListener('click', function () {
        btnfillters.forEach(function (btnfillter) {
            btnfillter.classList.remove('btn--primary')
        })
        btnfillter.classList.add('btn--primary')
        const productsElement = Array.from($$('.parent_product .product-item'));
        if (index == 0) {
            productsElement.sort((a,b) => b.dataset.soluong - a.dataset.soluong)
        }else if(index == 1){
            productsElement.sort((a,b) => b.dataset.id - a.dataset.id)
        }
        const parent = $('.parent_product');
        parent.innerHTML = '';
        productsElement.forEach((product) => {
            const productWrap = document.createElement('div')
            productWrap.classList.add('grid__column-2-4')
            productWrap.appendChild(product)
            parent.appendChild(productWrap);
        });
    });
});


const selectPrices = $$('.select-input__item a');
const spanSortPrice = $('.select-input__label')

selectPrices.forEach(function (selectPrice,index) {
    selectPrice.addEventListener('click', function (event) {
        event.preventDefault();
        spanSortPrice.textContent = selectPrice.textContent

        const productsElement = Array.from($$('.parent_product .product-item'));
        if (index == 0) {
            productsElement.sort((a,b) => a.dataset.price - b.dataset.price)
        }else if(index == 1){
            productsElement.sort((a,b) => b.dataset.price - a.dataset.price)
        }
        const parent = $('.parent_product');
        parent.innerHTML = '';
        productsElement.forEach((product) => {
            const productWrap = document.createElement('div')
            productWrap.classList.add('grid__column-2-4')
            productWrap.appendChild(product)
            parent.appendChild(productWrap);
        });

    });
});




// _____________________________________________________________________________Tìm theo khoảng giá ______________________________________________________________________//

const startPrice = $('.price_start')
const endPrice = $('.price_end')
const checkPrice = $('.check-price-btn')

checkPrice.addEventListener('click', function () {
    const start = startPrice.value;
    const end = endPrice.value;
    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.set('start', start);
    currentUrl.searchParams.set('end', end);
    window.location.href = currentUrl.toString();
})
