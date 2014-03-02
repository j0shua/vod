
var markItUpSettings = {
//    resizeHandle: true,
    onShiftEnter: {
        keepDefault: false,
        replaceWith: '\\\\\n'
    },
    onCtrlEnter: {
        keepDefault: false,
        openWith: '\n<p>',
        closeWith: '</p>'
    },
    onTab: {
        keepDefault: false,
        replaceWith: '    '
    },
    markupSet: [
        {
            name: 'หนา',
            key: 'B',
            openWith: '\\textbf{',
            closeWith: '}'

        },
        {
            name: 'Italic',
            key: 'I',
            openWith: '\\textit{',
            closeWith: '}'
        },
        {
            name: 'Stroke through',
            key: 'S',
            openWith: '\\st{',
            closeWith: '}'
        },
        {
            separator: '---------------'
        },
        {
            name: 'Bulleted List',
            openWith: '\\begin{itemize}\n\\item ',
            closeWith: '\n\\end{itemize}',
            placeHolder: 'Your text list'
        },
        {
            name: 'Numeric List',
            openWith: '\\begin{enumerate}\n\\item ',
            closeWith: '\n\\end{enumerate}',
            placeHolder: 'Your text list'
        },
        {
            separator: '---------------'
        },
        {
            name: 'Picture',
            key: 'P',
            beforeInsert: function(o) {
                image_browser(o.textarea);
            }
        },
        // \href{http://www.wikibooks.org}{wikibooks home}
        {
            name: 'Link',
            key: 'L',
            openWith: ' \\href{[![Link:!:http://]!]}{(!( [![Title]!])!)',
            closeWith: '}',
            placeHolder: 'Your text to link...'
        },
        {
            separator: '---------------'
        },
//    {
//        name:'Clean', 
//        className:'clean', 
//        replaceWith:function(markitup) {
//            return markitup.selection.replace(/<(.*?)>/g, "")
//        }
//    }
//    ,
        {
            name: 'preview',
            className: 'preview',
            beforeInsert: function(o) {
                preview();
            }
        }

    ]
};

var insert_image_openWith = function(image_file_path) {
    return "\\includegraphics[width=[![กว้าง (ซม.):!:15]!]cm]{" + image_file_path + "}";
};
function image_browser() {
    alert("โปรดสร้าง ฟังก์ชัน  \"image_browser\"  ก่อน");
}

