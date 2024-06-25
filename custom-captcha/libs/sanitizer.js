export default class sanitizer {
    html(html) {
        return html.replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }
}