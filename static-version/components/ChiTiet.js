// static-version/js/components/ChiTiet.js
const { h, Component } = window.preact;

export default class ChiTiet extends Component {
    render() {
        return h('div', null,
            h('div', { id: 'header' }),
            h('div', { class: 'app__container' },
                h('div', { class: 'grid' },
                    h('h1', null, 'Chi Tiết Sản Phẩm')
                )
            ),
            h('div', { id: 'footer' })
        );
    }
}