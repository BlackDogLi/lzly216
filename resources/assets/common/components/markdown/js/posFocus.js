//获取光标位置
function getCurrentPosition(txtNode) {
    let currentPos = 0;
    txtNode.focus();
    if (document.selection) { //IE
        let selectRange = document.selection.createRange();
        selectRange.moveStart('character', -txtNode.value.length());
        currentPos = selectRange.text.length;
    } else {
        //Firefox
        currentPos = txtNode.selectionStart;
    }
    return currentPos;
}

//设置光标位置
function setFocusPosition(txtNode, pos) {
    if (txtNode.setSelectionRange) {
        txtNode.focus();
        txtNode.setSelectionRange(pos, pos);
    } else {
        let range = txtNode.createTextRange();
        range.collapse(true);
        range.moveEnd('character', pos);
        range.moveStart('character', pos);
        range.select();
    }
}

//获取选中文字
function getSelectText() {
    let userSelection;
    if (window.getSelection) {
        userSelection = window.getSelection();
    } else if (document.selection) {
        userSelection = document.selection.createRange();
    }
    return userSelection;

}

/**
 *
 * @param txtNode 当前对象
 * @param value   插入文本
 */
function insertTextAfterFocus(txtNode, value) {
    let selectRange;

    if (document.selection) {
        txtNode.focus();
        selectRange = document.selection.createRange();
        selectRange.text = value;
        txtNode.focus();
    } else if (txtNode.selectionStart || txtNode.selectionStart == '0') {
        let strPos = txtNode.selectionStart;
        let endPos = txtNode.selectionEnd;
        let scrollTop = txtNode.scrollTop;
        txtNode.value = txtNode.value.substring(0, strPos) + value + txtNode.value.substring(endPos, txtNode.value.length)
        txtNode.focus();
        txtNode.selectionStart = strPos + value.length;
        txtNode.selectionEnd = strPos + value.length;
        txtNode.scrollTop = scrollTop;
    } else {
        txtNode.value += value;
        txtNode.focus();
    }
}

module.exports = {
    getCurrentPosition,
    setFocusPosition,
    getSelectText,
    insertTextAfterFocus
};