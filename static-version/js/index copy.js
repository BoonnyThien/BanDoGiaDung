// static-version/js/index.js
const { h, render, Component } = window.preact;
const { Router, route } = window.preactRouter;

// Hàm tiện ích
function $(selector) {
    return document.querySelector(selector);
}

function $$(selector) {
    return document.querySelectorAll(selector);
}

// Component Header
class Header extends Component {
    componentDidMount() {
        fetch('../static-version/components/header.html')
            .then(response => response.text())
            .then(data => {
                this.setState({ headerContent: data });
            });
    }

    render() {
        return h('div', { id: 'header', innerHTML: this.state?.headerContent || '' });
    }
}

// Component Footer
class Footer extends Component {
    componentDidMount() {
        fetch('../static-version/components/footer.html')
            .then(response => response.text())
            .then(data => {
                this.setState({ footerContent: data });
            });
    }

    render() {
        return h('div', { id: 'footer', innerHTML: this.state?.footerContent || '' });
    }
}

// Component Home (trang chính)
class Home extends Component {
    state = {
        categories: [],
        products: [],
        brands: [],
        currentPage: 1,
        itemsPerPage: 25
    };

    componentDidMount() {
        fetch('../web_giadung.json')
            .then(response => response.json())
            .then(jsonData => {
                const categories = jsonData.tables.find(table => table.name === "tbl_category").data;
                const products = jsonData.tables.find(table => table.name === "tbl_product").data;
                const brands = jsonData.tables.find(table => table.name === "tbl_brand").data;

                this.setState({ categories, products, brands });

                // Xử lý sự kiện sau khi DOM được render
                this.initializeEvents();
            });

        // Lấy page từ URL
        const urlParams = new URLSearchParams(window.location.search);
        const page = parseInt(urlParams.get('page')) || 1;
        this.setState({ currentPage: page });
    }

    initializeEvents() {
        // Danh mục fixed vị trí
        const category = $('.category');
        const gridColumn = $('.div_cha');
        let lastScrollPosition = 0;

        if (category && gridColumn) {
            window.addEventListener('scroll', () => {
                const scrollPosition = window.pageYOffset;
                const categoryRect = category.getBoundingClientRect();
                const gridRect = gridColumn.getBoundingClientRect();

                if (gridRect.top <= 80 && gridRect.bottom - categoryRect.height >= 120) {
                    category.style.position = 'fixed';
                    category.style.top = scrollPosition >= lastScrollPosition ? '80px' : '120px';
                    category.style.bottom = 'auto';
                } else {
                    category.style.position = 'absolute';
                    category.style.top = '0';
                    category.style.bottom = 'auto';
                }
                lastScrollPosition = scrollPosition;
            });
        }

        // Click yêu thích
        const likeButtons = $$('.product-item__like');
        likeButtons.forEach(item => {
            item.addEventListener('click', (event) => {
                event.preventDefault();
                item.classList.toggle('product-item__like--liked');
            });
        });

        // Slide chuyển động
        const imgPosition = $$('.slide_header-169 img');
        const imgContainer = $('.slide_header-169');
        const dotItems = $$('.slider_header-dot');

        if (imgPosition && imgContainer && dotItems) {
            let index = 0;
            let interval;

            imgPosition.forEach((image, idx) => {
                image.style.left = `${idx * 100}%`;
            });

            function startSlider() {
                interval = setInterval(imgSlider, 3000);
            }

            function imgSlider() {
                index = (index + 1) % imgPosition.length;
                slide(index);
            }

            function slide(idx) {
                imgContainer.style.left = `-${idx * 100}%`;
                dotItems.forEach(dot => dot.classList.remove('dot_active'));
                dotItems[idx].classList.add('dot_active');
            }

            dotItems.forEach((dot, idx) => {
                dot.addEventListener('click', () => {
                    index = idx;
                    slide(index);
                    clearInterval(interval);
                    startSlider();
                });
            });

            startSlider();
        }

        // Next/Previous sản phẩm
        const positionProduct = $$('.grid_no_wrap .product');
        const nextProduct = $('.next_product');
        const beforeProduct = $('.before_product');
        const containerProduct = $('.grid_no_wrap');

        if (nextProduct && beforeProduct && containerProduct && positionProduct.length > 0) {
            let currentIndex = 0;
            let styleLeft = 20;
            let maxIndex = positionProduct.length - 5;

            nextProduct.addEventListener('click', () => {
                if (currentIndex < maxIndex) {
                    currentIndex++;
                    containerProduct.style.left = `-${currentIndex * styleLeft}%`;
                }
            });

            beforeProduct.addEventListener('click', () => {
                if (currentIndex > 0) {
                    currentIndex--;
                    containerProduct.style.left = `-${currentIndex * styleLeft}%`;
                }
            });
        }
    }

    render() {
        const { categories, products, brands, currentPage, itemsPerPage } = this.state;

        // Sản phẩm HOT
        const hotProducts = products.filter(product => product.is_hot === "1");

        // Phân trang và sản phẩm gợi ý
        const urlParams = new URLSearchParams(window.location.search);
        const searchQuery = urlParams.get('search') || '';
        const totalPages = Math.ceil(products.length / itemsPerPage);
        const offset = (currentPage - 1) * itemsPerPage;

        const filteredProducts = searchQuery
            ? products.filter(product => product.product_name.toLowerCase().includes(searchQuery.toLowerCase()))
            : products;

        const paginatedProducts = filteredProducts.slice(offset, offset + itemsPerPage);

        return h('div', null,
            h(Header),
            h('div', { class: 'slider_header' },
                h('div', { class: 'slider_header-wrap grid' },
                    h('div', { class: 'slide_header-169' },
                        h('a', { href: '#' }, h('img', { class: 'slider_header-img', src: '../assets/img/slider_img/slide1.png', alt: 'Slide 1' })),
                        h('a', { href: '#' }, h('img', { class: 'slider_header-img', src: '../assets/img/slider_img/slide2.png', alt: 'Slide 2' })),
                        h('a', { href: '#' }, h('img', { class: 'slider_header-img', src: '../assets/img/slider_img/slide3.png', alt: 'Slide 3' })),
                        h('a', { href: '#' }, h('img', { class: 'slider_header-img', src: '../assets/img/slider_img/slide4.png', alt: 'Slide 4' })),
                        h('a', { href: '#' }, h('img', { class: 'slider_header-img', src: '../assets/img/slider_img/slide5.png', alt: 'Slide 5' }))
                    ),
                    h('div', { class: 'slider_header-dot-wrap' },
                        h('div', { class: 'slider_header-dot dot_active' }),
                        h('div', { class: 'slider_header-dot' }),
                        h('div', { class: 'slider_header-dot' }),
                        h('div', { class: 'slider_header-dot' }),
                        h('div', { class: 'slider_header-dot' })
                    )
                )
            ),
            h('div', { class: 'app__container' },
                h('div', { class: 'grid' },
                    h('div', { class: 'grid__row app__content' },
                        h('div', { class: 'grid__column-2 div_cha', style: 'position: relative; background-color: #fff;' },
                            h('div', { class: 'category' },
                                h('h3', { class: 'category__heading' },
                                    h('i', { class: 'category__heading-icon fa-solid fa-bars' }),
                                    'Danh mục'
                                ),
                                h('ul', { class: 'category-list', id: 'category-list' },
                                    categories.map(category =>
                                        h('li', { class: 'category-item' },
                                            h('a', { href: `/danhmuc/${category.category_id}`, class: 'category-item__link', onClick: (e) => { e.preventDefault(); route(`/danhmuc/${category.category_id}`); } }, category.category_name)
                                        )
                                    )
                                )
                            )
                        ),
                        h('div', { class: 'grid__column-10' },
                            h('div', { class: 'home-product', id: 'hot-products', style: 'padding: 15px; background-color: rgb(246, 232, 224); border-radius: 20px; margin-bottom: 40px;' },
                                h('div', { class: 'next_product' }, h('i', { class: 'fa-solid fa-angle-right' })),
                                h('div', { class: 'before_product' }, h('i', { class: 'fa-solid fa-angle-left' })),
                                h('div', { style: 'display: inline-block; padding: 10px 0px; margin: 10px 12px; font-size: 2.5rem; font-weight: 800; color: var(--primary-color); border-bottom: 2px solid var(--primary-color);' },
                                    'Sản phẩm HOT ', h('i', { class: 'fa-solid fa-fire' })
                                ),
                                h('div', { style: 'overflow: hidden;' },
                                    h('div', { class: 'grid_no_wrap', id: 'hot-products-list' },
                                        hotProducts.map(product => {
                                            const brand = brands.find(b => b.brand_id === product.brand_id);
                                            const discount = product.old_price > 0 ? Math.round(((product.old_price - product.new_price) / product.old_price) * 100) : 0;
                                            const hotClass = product.is_hot === "1" ? 'favourite_active' : '';
                                            const discountClass = product.old_price < product.new_price ? 'display_none' : '';

                                            return h('div', { class: 'grid__column-2-4 product', style: 'flex-shrink: 0;' },
                                                h('a', { class: 'product-item', href: `/chitiet/${product.product_id}`, onClick: (e) => { e.preventDefault(); route(`/chitiet/${product.product_id}`); } },
                                                    h('div', { class: 'product-item__img', style: `background-image: url(../assets/img/${product.main_image});` }),
                                                    h('h4', { class: 'product-item__name' }, product.product_name),
                                                    h('div', { class: 'product-item__price' },
                                                        h('span', { class: `product-item__price-old ${discountClass}` }, product.old_price, h('sup', null, 'đ')),
                                                        h('span', { class: 'product-item__price-current' }, product.new_price, h('sup', null, 'đ'))
                                                    ),
                                                    h('div', { class: 'product-item__ation' },
                                                        h('span', { class: 'product-item__like' },
                                                            h('i', { class: 'product-item__like-icon-empty fa-regular fa-heart', style: 'color: #000;' }),
                                                            h('i', { class: 'product-item__like-icon-fill fa-solid fa-heart' })
                                                        ),
                                                        h('div', { class: 'product-item__rating' },
                                                            h('i', { class: 'product-item__star--gold fa-solid fa-star' }),
                                                            h('i', { class: 'product-item__star--gold fa-solid fa-star' }),
                                                            h('i', { class: 'product-item__star--gold fa-solid fa-star' }),
                                                            h('i', { class: 'product-item__star--gold fa-solid fa-star' }),
                                                            h('i', { class: 'product-item__star--gold fa-solid fa-star' })
                                                        ),
                                                        h('span', { class: 'product-item__sold' }, product.quantity, ' đã bán')
                                                    ),
                                                    h('div', { class: 'product-item__origin' },
                                                        h('span', { class: 'product-item__brand' }, brand?.brand_name || 'Không xác định'),
                                                        h('span', { class: 'product-item__origin-name' }, product.origin)
                                                    ),
                                                    h('div', { class: `product-item__favourite ${hotClass}` },
                                                        h('i', { class: 'fa-solid fa-check' }),
                                                        h('span', null, 'yêu thích')
                                                    ),
                                                    h('div', { class: `product-item__sale-off ${discountClass}` },
                                                        h('span', { class: 'product-item__sale-off-percent' }, discount, '%'),
                                                        h('span', { class: 'product-item__sale-off-lable' }, 'Giảm')
                                                    )
                                                )
                                            );
                                        })
                                    )
                                )
                            ),
                            h('div', { class: 'home-product' },
                                h('h1', { id: 'suggestion-title', style: 'width: 100%; text-align: center; color: var(--primary-color); font-size: 2.5rem; margin-top: 0; padding: 30px 0 20px; background-color: var(--white-color); border-bottom: 5px solid var(--primary-color);' },
                                    searchQuery ? `Tìm kiếm: ${searchQuery}` : 'Gợi ý hôm nay'
                                ),
                                h('div', { class: 'grid__row', id: 'suggested-products' },
                                    paginatedProducts.length === 0
                                        ? h('div', { style: 'width: 100%; background-color: white; padding: 20px; margin: 15px; text-align: center;' },
                                            h('h1', { style: 'width: 100%;' }, 'Không có sản phẩm'),
                                            h('img', { src: '../assets/img/no_cart.png', alt: 'Không có sản phẩm', style: 'display: block; margin: 0 auto;' })
                                        )
                                        : paginatedProducts.map(product => {
                                            const brand = brands.find(b => b.brand_id === product.brand_id);
                                            const discount = product.old_price > 0 ? Math.round(((product.old_price - product.new_price) / product.old_price) * 100) : 0;
                                            const hotClass = product.is_hot === "1" ? 'favourite_active' : '';
                                            const discountClass = product.old_price < product.new_price ? 'display_none' : '';

                                            return h('div', { class: 'grid__column-2-4' },
                                                h('a', { class: 'product-item', href: `/chitiet/${product.product_id}`, onClick: (e) => { e.preventDefault(); route(`/chitiet/${product.product_id}`); } },
                                                    h('div', { class: 'product-item__img', style: `background-image: url(../assets/img/${product.main_image});` }),
                                                    h('h4', { class: 'product-item__name' }, product.product_name),
                                                    h('div', { class: 'product-item__price' },
                                                        h('span', { class: `product-item__price-old ${discountClass}` }, product.old_price, h('sup', null, 'đ')),
                                                        h('span', { class: 'product-item__price-current' }, product.new_price, h('sup', null, 'đ'))
                                                    ),
                                                    h('div', { class: 'product-item__ation' },
                                                        h('span', { class: 'product-item__like' },
                                                            h('i', { class: 'product-item__like-icon-empty fa-regular fa-heart', style: 'color: #000;' }),
                                                            h('i', { class: 'product-item__like-icon-fill fa-solid fa-heart' })
                                                        ),
                                                        h('div', { class: 'product-item__rating' },
                                                            h('i', { class: 'product-item__star--gold fa-solid fa-star' }),
                                                            h('i', { class: 'product-item__star--gold fa-solid fa-star' }),
                                                            h('i', { class: 'product-item__star--gold fa-solid fa-star' }),
                                                            h('i', { class: 'product-item__star--gold fa-solid fa-star' }),
                                                            h('i', { class: 'product-item__star--gold fa-solid fa-star' })
                                                        ),
                                                        h('span', { class: 'product-item__sold' }, product.quantity, ' đã bán')
                                                    ),
                                                    h('div', { class: 'product-item__origin' },
                                                        h('span', { class: 'product-item__brand' }, brand?.brand_name || 'Không xác định'),
                                                        h('span', { class: 'product-item__origin-name' }, product.origin)
                                                    ),
                                                    h('div', { class: `product-item__favourite ${hotClass}` },
                                                        h('i', { class: 'fa-solid fa-check' }),
                                                        h('span', null, 'yêu thích')
                                                    ),
                                                    h('div', { class: `product-item__sale-off ${discountClass}` },
                                                        h('span', { class: 'product-item__sale-off-percent' }, discount, '%'),
                                                        h('span', { class: 'product-item__sale-off-lable' }, 'Giảm')
                                                    )
                                                )
                                            );
                                        })
                                )
                            ),
                            h('ul', { class: 'pagination home-product__pagination', id: 'pagination' },
                                h('li', { class: 'pagination-item' },
                                    h('a', {
                                        href: `?page=${currentPage - 1}`,
                                        class: `pagination-item__link ${currentPage > 1 ? '' : 'a--disable'}`,
                                        onClick: (e) => {
                                            e.preventDefault();
                                            if (currentPage > 1) {
                                                this.setState({ currentPage: currentPage - 1 });
                                                route(`?page=${currentPage - 1}`);
                                            }
                                        }
                                    }, h('i', { class: 'pagination-item__icon fa-solid fa-angle-left' }))
                                ),
                                Array.from({ length: totalPages }, (_, i) => i + 1).map(num =>
                                    h('li', { class: `pagination-item ${currentPage === num ? 'pagination-item--active' : ''}` },
                                        h('a', {
                                            href: `?page=${num}`,
                                            class: 'pagination-item__link',
                                            onClick: (e) => {
                                                e.preventDefault();
                                                this.setState({ currentPage: num });
                                                route(`?page=${num}`);
                                            }
                                        }, num)
                                    )
                                ),
                                h('li', { class: 'pagination-item' },
                                    h('a', {
                                        href: `?page=${currentPage + 1}`,
                                        class: `pagination-item__link ${currentPage < totalPages ? '' : 'a--disable'}`,
                                        onClick: (e) => {
                                            e.preventDefault();
                                            if (currentPage < totalPages) {
                                                this.setState({ currentPage: currentPage + 1 });
                                                route(`?page=${currentPage + 1}`);
                                            }
                                        }
                                    }, h('i', { class: 'pagination-item__icon fa-solid fa-angle-right' }))
                                )
                            )
                        )
                    )
                )
            ),
            h(Footer)
        );
    }
}

// Component Danh Mục (danhmuc.html)
class DanhMuc extends Component {
    render() {
        return h('div', null,
            h(Header),
            h('div', { class: 'app__container' },
                h('div', { class: 'grid' },
                    h('h1', null, 'Danh Mục Sản Phẩm')
                )
            ),
            h(Footer)
        );
    }
}

// Component Chi Tiết Sản Phẩm (chitiet.html)
class ChiTiet extends Component {
    render() {
        return h('div', null,
            h(Header),
            h('div', { class: 'app__container' },
                h('div', { class: 'grid' },
                    h('h1', null, 'Chi Tiết Sản Phẩm')
                )
            ),
            h(Footer)
        );
    }
}

// Component App chính
const App = () => {
    return h(Router, null,
        h(Home, { path: '/' }),
        h(DanhMuc, { path: '/danhmuc/:id' }),
        h(ChiTiet, { path: '/chitiet/:id' })
    );
};

// Render ứng dụng
render(h(App), document.getElementById('app'));