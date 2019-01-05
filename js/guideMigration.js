function parseMd(md){

  //ul
  md = md.replace(/^\s*\n\*/gm, '<ul>\n*');
  md = md.replace(/^(\*.+)\s*\n([^\*])/gm, '$1\n</ul>\n\n$2');
  md = md.replace(/^\*(.+)/gm, '<li>$1</li>');
  
  //ol
  md = md.replace(/^\s*\n\d\./gm, '<ol>\n1.');
  md = md.replace(/^(\d\..+)\s*\n([^\d\.])/gm, '$1\n</ol>\n\n$2');
  md = md.replace(/^\d\.(.+)/gm, '<li>$1</li>');
  
  //blockquote
  md = md.replace(/^\>(.+)/gm, '<blockquote>$1</blockquote>');
  
  //h
  md = md.replace(/[\#]{6}[\s]{0,}(.+)/g, '<h6>$1</h6>');
  md = md.replace(/[\#]{5}[\s]{0,}(.+)/g, '<h5>$1</h5>');
  md = md.replace(/[\#]{4}[\s]{0,}(.+)/g, '<h4>$1</h4>');
  
  //links
  md = md.replace(/[\[]{1}([^\]]+)[\]]{1}[\(]{1}([^\)\"]+)(\"(.+)\")?[\)]{1}/g, '<a href="$2" title="$4">$1</a>');
  
  //font styles
  md = md.replace(/[\*\_]{2}([^\*\_]+)[\*\_]{2}/g, '<strong>$1</strong>');
  md = md.replace(/[\*\_]{1}([^\*\_]+)[\*\_]{1}/g, function(m){
  	if ( (/\<(\/)?(h\d|ul|ol|li|blockquote|pre|img|br|iframe)/.test(m)) || (/\[(\/)?(.+)/.test(m)) ) {
  		return '<em>'+m+'</em>';
  	}
  	return m;
  });
  md = md.replace(/[\~]{2}([^\~]+)[\~]{2}/g, '<del>$1</del>');
  
  //pre
  md = md.replace(/^\s*\n\`\`\`(([^\s]+))?/gm, '<pre class="$2">');
  md = md.replace(/^\`\`\`\s*\n/gm, '</pre>\n\n');
  
  //code
  md = md.replace(/[\`]{1}([^\`]+)[\`]{1}/g, '<code>$1</code>');

  //do the custom ones last to avoid getting processed as markdown
  md = md.replace(/[\#]{3}[\s]{0,}(.+)/g, '[guide_subheading title="$1"]');
  md = md.replace(/[\#]{2}[\s]{0,}(.+)/g, '[guide_heading title="$1"]');
  md = md.replace(/[\#]{1}[\s]{0,}(.+)/g, '[guide_heading title="$1"]');

  var img_srcs = /<img.*?src="(.*?)".*?>/.exec(md);

  //images
  md = md.replace(/\!\[([^\]]+)\]\(([^\)]+)\)/g, '[guide_image src="$2" alt="$1"]');
  //html images
  md = md.replace(/<img.*?src="(?:.*\/)?(.*?)".*?(?:alt="(.*?)")?.*?>/g, '[guide_image src="/wp-content/uploads/2019/01/$1" alt="$2" caption=""]');

  console.log(img_srcs);

  //p
  md = md.replace(/^\s*(\n)?(.+)/gm, function(m){
  	if ( (/\<(\/)?(h\d|ul|ol|li|blockquote|pre|img|br|iframe)/.test(m)) || (/\[(\/)?(.+)/.test(m)) ) {
  		//;
  	} else {
  		m = '<p>'+m+'</p>';
  	}
  	return m;
  });
  
  //strip p from pre
  md = md.replace(/(\<pre.+\>)\s*\n\<p\>(.+)\<\/p\>/gm, '$1$2');
  
  return md;
  
}

function guideMigration() {	
	var x = document.getElementById("inputArea").value;

	x = parseMd(x);

	document.getElementById("outputArea").value = x;
} 