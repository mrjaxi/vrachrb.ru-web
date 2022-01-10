var RTOOLBAR = {
  styles:
  { 
    title: RLANG.styles,
    func: 'show',
    dropdown: 
      {
       p:
       {
         title: RLANG.paragraph,
         exec: 'formatblock',
         param: '<p>'
       },
       blockquote:
       {
         title: RLANG.quote,
         exec: 'formatblock',  
         param: '<blockquote>',
         style: 'font-style: italic; color: #666; padding-left: 10px;'
       },
       pre:
       {  
         title: RLANG.code,
         exec: 'formatblock',
         param: '<pre>',
         style: 'font-family: monospace, sans-serif;'
       },
       
       h3:
       {
         title: RLANG.header2,       
         exec: 'formatblock', 
         param: '<h3>',           
         style: 'font-size: 18px; line-height: 24px;  font-weight: bold;'
       }
    },
    separator: true
  },
  
  h1:
  {
    title: 'Заголовок',
    html: 'Заголовок',
    exec: 'formatblock',
    param: '<h1>'
  },
  h2:
  {
    title: 'Подзаголовок',
    html: 'Подзаголовок',
    exec: 'formatblock',
    param: '<h2>'
  },
  bold:
  {
    title: RLANG.bold,
    exec: 'bold'
  }, 
  italic: 
  {
    title: RLANG.italic,
    exec: 'italic',
    separator: true    
  },
  insertunorderedlist:
  {
    title: '&bull; ' + RLANG.unorderedlist,
    exec: 'insertunorderedlist'
  },
  insertorderedlist: 
  {
    title: '1. ' + RLANG.orderedlist,
    exec: 'insertorderedlist'
  },
  html:
  {
    title: RLANG.html,
    func: 'toggle',
    separator: true
  }
};