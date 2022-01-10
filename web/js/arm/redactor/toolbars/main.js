var RTOOLBAR = 
{
	html:
	{
		title: RLANG.html,
		func: 'toggle',
		separator: true
	},	
	//styles:
	//{
	//	title: RLANG.styles,
	//	func: 'show',
	//	dropdown:
	//    {
	//		 p:
	//		 {
	//		 	title: RLANG.paragraph,
	//		 	exec: 'formatblock',
	//		 	param: '<p>'
	//		 },
	//		 blockquote:
	//		 {
	//		 	title: RLANG.quote,
	//		 	exec: 'formatblock',
	//		 	param: '<blockquote>',
	//		 	style: 'font-style: italic; color: #666; padding-left: 10px;'
	//		 },
	//		 pre:
	//		 {
	//		 	title: RLANG.code,
	//		 	exec: 'formatblock',
	//		 	param: '<pre>',
	//		 	style: 'font-family: monospace, sans-serif;'
	//		 },
	//		 h2:
	//		 {
	//		 	title: RLANG.header1,
	//		 	exec: 'formatblock',
	//		 	param: '<h2>',
	//		 	style: 'font-size: 24px; line-height: 34px; font-weight: bold;'
	//		 },
	//		 h3:
	//		 {
	//		 	title: RLANG.header2,
	//		 	exec: 'formatblock',
	//		 	param: '<h3>',
	//		 	style: 'font-size: 18px; line-height: 24px;  font-weight: bold;'
	//		 }
	//	},
	//	separator: true
	//},
	bold:
	{ 
		title: RLANG.bold,
		exec: 'bold',
	 	param: null	
	}, 
	italic:
	{
		title: RLANG.italic,
		exec: 'italic',
	 	param: null
	},
	deleted:
	{
		title: RLANG.deleted,
		exec: 'strikethrough',
	 	param: null,
		separator: true	 		
	},	
	insertunorderedlist:
	{
		title: '&bull; ' + RLANG.unorderedlist,
		exec: 'insertunorderedlist',
		param: null
	},
	insertorderedlist:
	{
		title: '1. ' + RLANG.orderedlist,
		exec: 'insertorderedlist',
		param: null
	},
	image:
	{
		title: RLANG.image,
		func: 'showImage'
	},
	video:
	{
		title: RLANG.video,
		func: 'showVideo'
	},
	link:
	{
		title: RLANG.link,
		func: 'show',
		dropdown:
		{
			link:
			{
				title: RLANG.link_insert,
				func: 'showLink'
			},
			unlink:
			{
				title: RLANG.unlink,
				exec: 'unlink',
			 	param: null
			}
		},
		separator: true
	},
	horizontalrule:
	{
		exec: 'inserthorizontalrule',
		name: 'horizontalrule',
		title: RLANG.horizontalrule
	}
};