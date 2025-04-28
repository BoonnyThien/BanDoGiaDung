// static-version/js/components/DanhMuc.js
const { h, Component } = window.preact;

export default class DanhMuc extends Component {
    render() {
        return h('div', null,
            h('div', { id: 'header' }), // Tải header động
            h('div', { class: 'app__container' },
                h('div', { class: 'grid' },
                    h('h1', null, 'Danh Mục Sản Phẩm')
                )
            ),
            h('div', { id: 'footer' }) // Tải footer động
        );
    }
}