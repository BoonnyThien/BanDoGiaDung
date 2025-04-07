function $(selector) {
    return document.querySelector(selector);
}

function $$(selector) {
    return document.querySelectorAll(selector);
}

//_______________________________________________________________Danh mục fixed vị trí___________________________________________________________________________//

const category = $('.category');
const gridColumn = $('.div_cha');
let abc = 0;
window.addEventListener('scroll', function () {
    let x = window.pageYOffset
    const categoryRect = category.getBoundingClientRect();
    const gridRect = gridColumn.getBoundingClientRect();

    // Kiểm tra nếu khoảng cách từ top của gridColumn đến top của viewport nhỏ hơn hoặc bằng 100px
    if (gridRect.top <= 80 && gridRect.bottom - categoryRect.height >= 120) {
        category.style.position = 'fixed';
        if (x >= abc) {
            category.style.top = '80px'; // Cố định tại top của viewport
        } else {
            category.style.top = '120px'; // Cố định tại top của viewport
        }
        abc = x
        category.style.bottom = 'auto'; // Reset bottom
    }
    // Khi category còn trong div cha nhưng chưa chạm tới top
    else {
        category.style.position = 'absolute';
        category.style.top = '0'; // Đặt tại top của gridColumn
        category.style.bottom = 'auto'; // Reset bottom
        // }
    }
});




// ______________________________________________________________________Click yêu thích_______________________________________________________________________//

$$('.product-item__like').forEach(item => {
    item.addEventListener('click', function (event) {
        event.preventDefault();
        item.classList.toggle('product-item__like--liked');
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



// _____________________________________________________________________________Next product, before product __________________________________________________________________//

const positionProduct = $$('.grid_no_wrap .product');
const nextProduct = $('.next_product')
const beforeProduct = $('.before_product')
const containerProduct = $('.grid_no_wrap')
const pageDetail = $('.product_detail')
let currentIndex = 0;
let maxIndex = positionProduct.length - 5;
let styleleft = 20;
if (pageDetail) {
    if (pageDetail.getAttribute('data-page') === 'detail') {
        styleleft = 16.667;
        maxIndex = positionProduct.length - 6;
    }
}
if (nextProduct && beforeProduct) {
    nextProduct.addEventListener('click', function () {
        if (currentIndex < maxIndex) {
            currentIndex++;
            containerProduct.style.left = "-" + currentIndex * styleleft + "%";
        }
    });

    beforeProduct.addEventListener('click', function () {
        if (currentIndex > 0) {
            currentIndex--;
            containerProduct.style.left = "-" + currentIndex * styleleft + "%";
        }
    });
}