
var xelatex_setting = {
    onShiftEnter:  	{
        keepDefault:false, 
        replaceWith:'\\\\\n'
    },
    onCtrlEnter:  	{
        keepDefault:false, 
        openWith:'\n<p>', 
        closeWith:'</p>'
    },
    onTab:    		{
        keepDefault:false, 
        replaceWith:'    '
    },
    markupSet:  [ 	
    {
        name:'หนา', 
        key:'B', 
        openWith:'\\textbf{', 
        closeWith:'}'
        
    },
    {
        name:'Italic', 
        key:'I', 
        openWith:'\\textit{', 
        closeWith:'}'
    },
    {
        name:'Stroke through', 
        key:'S', 
        openWith:'\\st{', 
        closeWith:'}'
    },
    {
        separator:'---------------'
    },
    {
        name:'Bulleted List', 
        openWith:'\\begin{itemize}\n\\item ', 
        closeWith:'\n\\end{itemize}',
        placeHolder:'Your text list'
    },
    {
        name:'Numeric List', 
        openWith:'\\begin{enumerate}\n\\item ', 
        closeWith:'\n\\end{enumerate}',
        placeHolder:'Your text list'
    },

    {
        separator:'---------------'
    },
    {
        name:'Picture', 
        key:'P', 
        beforeInsert:function(o){
            image_browser(o.textarea);
        }
    },
    // \href{http://www.wikibooks.org}{wikibooks home}
    {
        name:'Link', 
        key:'L', 
        openWith:' \\href{[![Link:!:http://]!]}{(!( [![Title]!])!)', 
        closeWith:'}', 
        placeHolder:'Your text to link...'
    },
    {
        separator:'---------------'
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
        name:'preview', 
        className:'preview', 
        beforeInsert:function(o){
            preview();
        }
    }
		
    ]
}



var bbcode_setting = {
	previewParserPath:	'', // path to your BBCode parser
	markupSet: [
		{name:'Bold', key:'B', openWith:'[b]', closeWith:'[/b]'},
		{name:'Italic', key:'I', openWith:'[i]', closeWith:'[/i]'},
		{name:'Underline', key:'U', openWith:'[u]', closeWith:'[/u]'},
		{separator:'---------------' },
		{name:'Picture', key:'P', replaceWith:'[img][![Url]!][/img]'},
		{name:'Link', key:'L', openWith:'[url=[![Url]!]]', closeWith:'[/url]', placeHolder:'Your text to link here...'},
		{separator:'---------------' },
		{name:'Size', key:'S', openWith:'[size=[![Text size]!]]', closeWith:'[/size]',
		dropMenu :[
			{name:'Big', openWith:'[size=200]', closeWith:'[/size]' },
			{name:'Normal', openWith:'[size=100]', closeWith:'[/size]' },
			{name:'Small', openWith:'[size=50]', closeWith:'[/size]' }
		]},
		{separator:'---------------' },
		{name:'Bulleted list', openWith:'[list]\n', closeWith:'\n[/list]'},
		{name:'Numeric list', openWith:'[list=[![Starting number]!]]\n', closeWith:'\n[/list]'}, 
		{name:'List item', openWith:'[*] '},
		{separator:'---------------' },
		{name:'Quotes', openWith:'[quote]', closeWith:'[/quote]'},
		{name:'Code', openWith:'[code]', closeWith:'[/code]'}, 
		{separator:'---------------' },
		{name:'Clean', className:"clean", replaceWith:function(markitup) { return markitup.selection.replace(/\[(.*?)\]/g, "") } },
		{name:'Preview', className:"preview", call:'preview' }
	]
}
function image_browser(){
    alert("โปรดสร้าง ฟังก์ชัน  \"image_browser\"  ก่อน");
}

