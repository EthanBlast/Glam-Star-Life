jQuery(document).ready(function(b){var a=esc_html=function(c){return String(c).replace(/"/g,"&quot;").replace(/\</g,"&lt;").replace(/\>/g,"&gt;")};if(location.href.match(/page\=ws-plugin--s2member-mms-options/)){b("select#ws-plugin--s2member-mms-registration-file").change(function(){if(b(this).val()==="wp-signup"){b("div#ws-plugin--s2member-mms-registration-support-package-details-wrapper").show(),b("div.ws-plugin--s2member-mms-registration-wp-login, table.ws-plugin--s2member-mms-registration-wp-login").hide(),b("div.ws-plugin--s2member-mms-registration-wp-signup, table.ws-plugin--s2member-mms-registration-wp-signup").show()}else{if(b(this).val()==="wp-login"){b("div#ws-plugin--s2member-mms-registration-support-package-details-wrapper").hide(),b("div.ws-plugin--s2member-mms-registration-wp-login, table.ws-plugin--s2member-mms-registration-wp-login").show(),b("div.ws-plugin--s2member-mms-registration-wp-signup, table.ws-plugin--s2member-mms-registration-wp-signup").hide()}}b("div.ws-plugin--s2member-mms-registration-wp-signup-blogs-level0, table.ws-plugin--s2member-mms-registration-wp-signup-blogs-level0")[((b("select#ws-plugin--s2member-mms-registration-grants").val()==="all")?"show":"hide")](),b("input#ws-plugin--s2member-mms-registration-blogs-level0").val(((b("select#ws-plugin--s2member-mms-registration-grants").val()==="all")?"1":"0"))}).trigger("change");b("select#ws-plugin--s2member-mms-registration-grants").change(function(){b("select#ws-plugin--s2member-mms-registration-file").trigger("change")})}else{if(location.href.match(/page\=ws-plugin--s2member-options/)){ws_plugin__s2member_generateSecurityKey=function(){var f=function(h,g){h=(arguments.length<1)?0:h;g=(arguments.length<2)?2147483647:g;return Math.floor(Math.random()*(g-h+1))+h};var e="ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()";for(var d=0,c="";d<56;d++){c+=e.substr(f(0,e.length-1),1)}b("input#ws-plugin--s2member-sec-encryption-key").val(c);return false};ws_plugin__s2member_enableSecurityKey=function(){if(confirm("Edit Key? Are you sure?\nThis could break your installation!\n\n*Note* If you've been testing s2Member, feel free to change this Key before you go live. Just don't go live, and then change it. You'll have some very unhappy Customers. Data corruption WILL occur!\n\nFor your safety, s2Member keeps a history of the last 10 Keys that you've used. If you get yourself into a real situation, s2Member will let you revert back to a previous Key.")){b("input#ws-plugin--s2member-sec-encryption-key").attr("disabled",false)}return false};ws_plugin__s2member_securityKeyHistory=function(){b("div#ws-plugin--s2member-sec-encryption-key-history").toggle();return false};if(b("input#ws-plugin--s2member-custom-reg-fields").length&&b("div#ws-plugin--s2member-custom-reg-field-configuration").length){(function(){var c=b("input#ws-plugin--s2member-custom-reg-fields");var f=b("div#ws-plugin--s2member-custom-reg-field-configuration");var l=(c.val())?b.JSON.parse(c.val()):[];l=(l instanceof Array)?l:[];var k='<div id="ws-plugin--s2member-custom-reg-field-configuration-tools"></div>';var r='<table id="ws-plugin--s2member-custom-reg-field-configuration-table"></table>';f.html(k+r);var i=b("div#ws-plugin--s2member-custom-reg-field-configuration-tools");var o=b("table#ws-plugin--s2member-custom-reg-field-configuration-table");ws_plugin__s2member_customRegFieldTypeChange=function(t){var s,v,u=b(t).val();s="tr.ws-plugin--s2member-custom-reg-field-configuration-tools-form-options";v="tr.ws-plugin--s2member-custom-reg-field-configuration-tools-form-expected";if(u.match(/^(text|textarea|checkbox|pre_checkbox)$/)){b(s).css("display","none"),b(s).prev("tr").css("display","none")}else{b(s).css("display",""),b(s).prev("tr").css("display","")}if(u.match(/^(select|selects|checkboxes|radios)$/)){b(v).css("display","none"),b(v).prev("tr").css("display","none")}else{b(v).css("display",""),b(v).prev("tr").css("display","")}};ws_plugin__s2member_customRegFieldDelete=function(t){var s=new Array();for(var u=0;u<l.length;u++){if(u!==t){s.push(l[u])}}l=s,q(),p()};ws_plugin__s2member_customRegFieldMoveUp=function(u){if(typeof l[u]==="object"&&typeof l[u-1]==="object"){var t=l[u-1],s=l[u];l[u-1]=s,l[u]=t;q(),p()}};ws_plugin__s2member_customRegFieldMoveDown=function(u){if(typeof l[u]==="object"&&typeof l[u+1]==="object"){var s=l[u+1],t=l[u];l[u+1]=t,l[u]=s;q(),p()}};ws_plugin__s2member_customRegFieldCreate=function(){var s=b("table#ws-plugin--s2member-custom-reg-field-configuration-tools-form"),t={};b(':input[property!=""]',s).each(function(){var v=b(this),u=v.attr("property"),w=b.trim(v.val());t[u]=w});if((t=d(t))){l.push(t),q(),j(),p(),n();setTimeout(function(){var u="tr.ws-plugin--s2member-custom-reg-field-configuration-table-row-"+(l.length-1);alert('Field created successfully.\n* Remember to "Save Changes".');b(u).effect("highlight",1000)},500)}};ws_plugin__s2member_customRegFieldUpdate=function(t){var s=b("table#ws-plugin--s2member-custom-reg-field-configuration-tools-form"),u={};b(':input[property!=""]',s).each(function(){var x=b(this),w=x.attr("property"),y=b.trim(x.val());u[w]=y});if((u=d(u,t))){l[t]=u,q(),j(),p(),n();var v="tr.ws-plugin--s2member-custom-reg-field-configuration-table-row-"+t;b(v).effect("highlight",1000)}};ws_plugin__s2member_customRegFieldAdd=function(){j(true)};ws_plugin__s2member_customRegFieldEdit=function(s){j(false,s),n()};ws_plugin__s2member_customRegFieldCancel=function(){j(),n()};var d=function(w,u){var s=(typeof u==="number"&&typeof l[u]==="object")?true:false,x=[],t,v;if(typeof w!=="object"){x.push("Invalid field object. Please try again.")}else{if(!w.id){x.push("Unique Field ID:\nThis is required. Please try again.")}else{if(h(w.id)&&(!s||w.id!==l[u].id)){x.push("Unique Field ID:\nThat Field ID already exists. Please try again.")}}if(!w.label){x.push("Field Label/Description:\nThis is required. Please try again.")}if(w.type.match(/^(select|selects|checkboxes|radios)$/)){w.expected="";if(!w.options){x.push("Option Configuration File:\nThis is required. Please try again.")}else{t=w.options.split(/[\r\n]+/);for(v=0;v<t.length;v++){t[v]=b.trim(t[v]);if(!t[v].match(/^([^\|]*)(\|)([^\|]*)(\|default)?$/)){x.push("Option Configuration File:\nInvalid configuration at line #"+(v+1)+".");break}}w.options=b.trim(t.join("\n"))}}else{w.options=""}if(!(w.levels=w.levels.replace(/ /g,""))){x.push("Applicable Levels:\nThis is required. Please try again.")}else{if(!w.levels.match(/^(all|[0-9,]+)$/)){x.push("Applicable Levels:\nShould be comma delimited Levels, or just type: all.\n( examples: 0,1,2,3,4 or type the word: all )")}}if(w.classes&&w.classes.match(/[^a-z 0-9 _ \-]/i)){x.push("CSS Classes:\nContains invalid characters. Please try again.\n( only: alphanumerics, underscores, hyphens, spaces )")}if(w.styles&&w.styles.match(/["\=\>\<]/)){x.push('CSS Styles:\nContains invalid characters. Please try again.\n( do NOT use these characters: = " < > )')}if(w.attrs&&w.attrs.match(/[\>\<]/)){x.push("Other Attributes:\nContains invalid characters. Please try again.\n( do NOT use these characters: < > )")}}if(x.length>0){alert(x.join("\n\n"));return false}else{return w}};var q=function(){c.val(((l.length>0)?b.JSON.stringify(l):""))};var m=function(s){return(typeof s==="string")?b.trim(s).toLowerCase().replace(/[^a-z0-9]/g,"_"):""};var g=function(t){var s={text:"Text ( single line )",textarea:"Textarea ( multi-line )",select:"Select Menu ( drop-down )",selects:"Select Menu ( multi-option )",checkbox:"Checkbox ( single )",pre_checkbox:"Checkbox ( pre-checked )",checkboxes:"Checkboxes ( multi-option )",radios:"Radio Buttons ( multi-option )"};if(typeof s[t]==="string"){return s[t]}return""};var h=function(s){for(var t=0;t<l.length;t++){if(l[t].id===s){return true}}};var n=function(){scrollTo(0,b("div.ws-plugin--s2member-custom-reg-fields-section").offset()["top"]-100)};var j=function(s,A){var x=0,z="",t="",D=0,y=0,v={id:"",label:"",type:"text",options:"",expected:"",required:"yes",levels:"all",editable:"yes",classes:"",styles:"",attrs:""};var u=(typeof A==="number"&&typeof l[A]==="object")?true:false,C=(s||u)?true:false,B=(u)?l[A]:v;z+='<a href="#" onclick="ws_plugin__s2member_customRegFieldAdd(); return false;">Add New Field</a>';tb_remove(),b("div#ws-plugin--s2member-custom-reg-field-configuration-thickbox-tools-form").remove();if(C){t+='<div id="ws-plugin--s2member-custom-reg-field-configuration-thickbox-tools-form">';t+='<table id="ws-plugin--s2member-custom-reg-field-configuration-tools-form">';t+="<tbody>";t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-type">';t+='<td colspan="2">';t+='<label for="ws-plugin--s2member-custom-reg-field-configuration-tools-form-type">Form Field Type: *</label>';t+="</td>";t+="</tr>";t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-type">';t+='<td colspan="2">';t+='<select property="type" onchange="ws_plugin__s2member_customRegFieldTypeChange(this);" id="ws-plugin--s2member-custom-reg-field-configuration-tools-form-type">';t+='<option value="text"'+((B.type==="text")?' selected="selected"':"")+'">'+esc_html(g("text"))+"</option>";t+='<option value="textarea"'+((B.type==="textarea")?' selected="selected"':"")+'">'+esc_html(g("textarea"))+"</option>";t+='<option value="select"'+((B.type==="select")?' selected="selected"':"")+'">'+esc_html(g("select"))+"</option>";t+='<option value="selects"'+((B.type==="selects")?' selected="selected"':"")+'">'+esc_html(g("selects"))+"</option>";t+='<option value="checkbox"'+((B.type==="checkbox")?' selected="selected"':"")+'">'+esc_html(g("checkbox"))+"</option>";t+='<option value="pre_checkbox"'+((B.type==="pre_checkbox")?' selected="selected"':"")+'">'+esc_html(g("pre_checkbox"))+"</option>";t+='<option value="checkboxes"'+((B.type==="checkboxes")?' selected="selected"':"")+'">'+esc_html(g("checkboxes"))+"</option>";t+='<option value="radios"'+((B.type==="radios")?' selected="selected"':"")+'">'+esc_html(g("radios"))+"</option>";t+="</select>";t+="</td>";t+="</tr>";t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-spacer"><td colspan="2">&nbsp;</td></tr>';t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-label">';t+='<td colspan="2">';t+='<label for="ws-plugin--s2member-custom-reg-field-configuration-tools-form-label">Field Label/Desc: *</label>';t+="</td>";t+="</tr>";t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-label">';t+='<td colspan="2">';t+='<input type="text" property="label" value="'+a(B.label)+'" id="ws-plugin--s2member-custom-reg-field-configuration-tools-form-label" /><br />';t+="<small>Examples: <code>Choose Country</code>, <code>Street Address</code></small>";t+="</td>";t+="</tr>";t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-spacer"><td colspan="2">&nbsp;</td></tr>';t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-id">';t+='<td colspan="2">';t+='<label for="ws-plugin--s2member-custom-reg-field-configuration-tools-form-id">Unique Field ID: *</label></label>';t+="</td>";t+="</tr>";t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-id">';t+='<td colspan="2">';t+='<input type="text" property="id" value="'+a(B.id)+'" maxlength="25" id="ws-plugin--s2member-custom-reg-field-configuration-tools-form-id" /><br />';t+="<small>Examples: <code>country_code</code>, <code>street_address</code></small><br />";t+='<small>e.g. <code>&lt;?php echo get_user_field("country_code"); ?&gt;</code></small>';t+="</td>";t+="</tr>";t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-spacer"><td colspan="2">&nbsp;</td></tr>';t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-required">';t+='<td colspan="2">';t+='<label for="ws-plugin--s2member-custom-reg-field-configuration-tools-form-required">Field Required: *</label>';t+="</td>";t+="</tr>";t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-required">';t+='<td colspan="2">';t+='<select property="required" id="ws-plugin--s2member-custom-reg-field-configuration-tools-form-required">';t+='<option value="yes"'+((B.required==="yes")?' selected="selected"':"")+'">Yes ( required )</option>';t+='<option value="no"'+((B.required==="no")?' selected="selected"':"")+'">No ( optional )</option>';t+="</select><br />";t+='<small>If <code>yes</code>, only Users/Members will be "required" to enter this field.</small><br />';t+="<small>* Administrators are exempt from this requirement.</small>";t+="</td>";t+="</tr>";t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-spacer"'+((B.type.match(/^(text|textarea|checkbox|pre_checkbox)$/))?' style="display:none;"':"")+'><td colspan="2">&nbsp;</td></tr>';t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-options"'+((B.type.match(/^(text|textarea|checkbox|pre_checkbox)$/))?' style="display:none;"':"")+">";t+='<td colspan="2">';t+='<label for="ws-plugin--s2member-custom-reg-field-configuration-tools-form-options">Option Configuration File: * ( one option per line )</label><br />';t+="<small>Use a pipe <code>|</code> delimited format: <code>option value|option label</code></small>";t+="</td>";t+="</tr>";t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-options"'+((B.type.match(/^(text|textarea|checkbox|pre_checkbox)$/))?' style="display:none;"':"")+">";t+='<td colspan="2">';t+='<textarea property="options" rows="3" wrap="off" spellcheck="false" id="ws-plugin--s2member-custom-reg-field-configuration-tools-form-options">'+esc_html(B.options)+"</textarea><br />";t+="Here is a quick example:<br />";t+="<small>You can also specify a <em>default</em> option:</small><br />";t+="<code>US|United States|default</code><br />";t+="<code>CA|Canada</code><br />";t+="<code>VI|Virgin Islands (U.S.)</code>";t+="</td>";t+="</tr>";t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-spacer"'+((B.type.match(/^(select|selects|checkboxes|radios)$/))?' style="display:none;"':"")+'><td colspan="2">&nbsp;</td></tr>';t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-expected"'+((B.type.match(/^(select|selects|checkboxes|radios)$/))?' style="display:none;"':"")+">";t+='<td colspan="2">';t+='<label for="ws-plugin--s2member-custom-reg-field-configuration-tools-form-expected">Expected Format: *</label>';t+="</td>";t+="</tr>";t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-expected"'+((B.type.match(/^(select|selects|checkboxes|radios)$/))?' style="display:none;"':"")+">";t+='<td colspan="2">';t+='<select property="expected" id="ws-plugin--s2member-custom-reg-field-configuration-tools-form-expected">';t+='<option value=""'+((B.expected==="")?' selected="selected"':"")+'">Anything Goes</option>';t+='<option disabled="disabled"></option>';t+='<optgroup label="Specific Input Types">';t+='<option value="numeric-wp-commas"'+((B.expected==="numeric-wp-commas")?' selected="selected"':"")+'">Numeric ( with or without decimals, commas allowed )</option>';t+='<option value="numeric"'+((B.expected==="numeric")?' selected="selected"':"")+'">Numeric ( with or without decimals, no commas )</option>';t+='<option value="integer"'+((B.expected==="integer")?' selected="selected"':"")+'">Integer ( whole number, without any decimals )</option>';t+='<option value="integer-gt-0"'+((B.expected==="integer-gt-0")?' selected="selected"':"")+'">Integer > 0 ( whole number, no decimals, greater than 0 )</option>';t+='<option value="float"'+((B.expected==="float")?' selected="selected"':"")+'">Float ( floating point number, decimals required )</option>';t+='<option value="float-gt-0"'+((B.expected==="float-gt-0")?' selected="selected"':"")+'">Float > 0 ( floating point number, decimals required, greater than 0 )</option>';t+='<option value="date"'+((B.expected==="date")?' selected="selected"':"")+'">Date ( required date format: dd/mm/yyyy )</option>';t+='<option value="email"'+((B.expected==="email")?' selected="selected"':"")+'">Email ( require valid email )</option>';t+='<option value="url"'+((B.expected==="url")?' selected="selected"':"")+'">Full URL ( starting with http or https )</option>';t+='<option value="domain"'+((B.expected==="domain")?' selected="selected"':"")+'">Domain Name ( domain name only, without http )</option>';t+='<option value="phone"'+((B.expected==="phone")?' selected="selected"':"")+'">Phone # ( 10 digits w/possible hyphens,spaces,brackets )</option>';t+='<option value="uszip"'+((B.expected==="uszip")?' selected="selected"':"")+'">US Zipcode ( 5-9 digits w/possible hyphen )</option>';t+='<option value="cazip"'+((B.expected==="cazip")?' selected="selected"':"")+'">Canadian Zipcode ( 6 alpha-numerics w/possible space )</option>';t+='<option value="uczip"'+((B.expected==="uczip")?' selected="selected"':"")+'">US/Canadian Zipcode ( either a US or Canadian zipcode )</option>';t+="</optgroup>";t+='<option disabled="disabled"></option>';t+='<optgroup label="Any Character Combination">';for(x=1;x<=25;x++){t+='<option value="any-'+x+'"'+((B.expected==="any-"+x)?' selected="selected"':"")+'">Any Character Combination ( '+x+" character minimum )</option>";t+='<option value="any-'+x+'-e"'+((B.expected==="any-"+x+"-e")?' selected="selected"':"")+'">Any Character Combination ( exactly '+x+" character"+((x>1)?"s":"")+" )</option>"}t+="</optgroup>";t+='<option disabled="disabled"></option>';t+='<optgroup label="Alphanumerics, Spaces &amp; Punctuation Only">';for(x=1;x<=25;x++){t+='<option value="alphanumerics-spaces-punctuation-'+x+'"'+((B.expected==="alphanumerics-spaces-punctuation-"+x)?' selected="selected"':"")+'">Alphanumerics, Spaces &amp; Punctuation ( '+x+" character minimum )</option>";t+='<option value="alphanumerics-spaces-punctuation-'+x+'-e"'+((B.expected==="alphanumerics-spaces-punctuation-"+x+"-e")?' selected="selected"':"")+'">Alphanumerics, Spaces &amp; Punctuation ( exactly '+x+" character"+((x>1)?"s":"")+" )</option>"}t+="</optgroup>";t+='<option disabled="disabled"></option>';t+='<optgroup label="Alphanumerics &amp; Spaces Only">';for(x=1;x<=25;x++){t+='<option value="alphanumerics-spaces-'+x+'"'+((B.expected==="alphanumerics-spaces-"+x)?' selected="selected"':"")+'">Alphanumerics &amp; Spaces ( '+x+" character minimum )</option>";t+='<option value="alphanumerics-spaces-'+x+'-e"'+((B.expected==="alphanumerics-spaces-"+x+"-e")?' selected="selected"':"")+'">Alphanumerics &amp; Spaces ( exactly '+x+" character"+((x>1)?"s":"")+" )</option>"}t+="</optgroup>";t+='<option disabled="disabled"></option>';t+='<optgroup label="Alphanumerics &amp; Punctuation Only">';for(x=1;x<=25;x++){t+='<option value="alphanumerics-punctuation-'+x+'"'+((B.expected==="alphanumerics-punctuation-"+x)?' selected="selected"':"")+'">Alphanumerics &amp; Punctuation ( '+x+" character minimum )</option>";t+='<option value="alphanumerics-punctuation-'+x+'-e"'+((B.expected==="alphanumerics-punctuation-"+x+"-e")?' selected="selected"':"")+'">Alphanumerics &amp; Punctuation ( exactly '+x+" character"+((x>1)?"s":"")+" )</option>"}t+="</optgroup>";t+='<option disabled="disabled"></option>';t+='<optgroup label="Alphanumerics Only">';for(x=1;x<=25;x++){t+='<option value="alphanumerics-'+x+'"'+((B.expected==="alphanumerics-"+x)?' selected="selected"':"")+'">Alphanumerics ( '+x+" character minimum )</option>";t+='<option value="alphanumerics-'+x+'-e"'+((B.expected==="alphanumerics-"+x+"-e")?' selected="selected"':"")+'">Alphanumerics ( exactly '+x+" character"+((x>1)?"s":"")+" )</option>"}t+="</optgroup>";t+='<option disabled="disabled"></option>';t+='<optgroup label="Alphabetics Only">';for(x=1;x<=25;x++){t+='<option value="alphabetics-'+x+'"'+((B.expected==="alphabetics-"+x)?' selected="selected"':"")+'">Alphabetics ( '+x+" character minimum )</option>";t+='<option value="alphabetics-'+x+'-e"'+((B.expected==="alphabetics-"+x+"-e")?' selected="selected"':"")+'">Alphabetics ( exactly '+x+" character"+((x>1)?"s":"")+" )</option>"}t+="</optgroup>";t+='<option disabled="disabled"></option>';t+='<optgroup label="Numeric Digits Only">';for(x=1;x<=25;x++){t+='<option value="numerics-'+x+'"'+((B.expected==="numerics-"+x)?' selected="selected"':"")+'">Numeric Digits ( '+x+" digit minimum )</option>";t+='<option value="numerics-'+x+'-e"'+((B.expected==="numerics-"+x+"-e")?' selected="selected"':"")+'">Numeric Digits ( exactly '+x+" digit"+((x>1)?"s":"")+" )</option>"}t+="</optgroup>";t+="</select><br />";t+="<small>Only Users/Members will be required to meet this criteria.</small><br />";t+="<small>* Administrators are exempt from this.</small>";t+="</td>";t+="</tr>";t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-spacer"><td colspan="2">&nbsp;</td></tr>';t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-levels">';t+='<td colspan="2">';t+='<label for="ws-plugin--s2member-custom-reg-field-configuration-tools-form-levels">Applicable Membership Levels: *</label>';t+="</td>";t+="</tr>";t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-levels">';t+='<td colspan="2">';t+='<input type="text" property="levels" value="'+a(B.levels)+'" id="ws-plugin--s2member-custom-reg-field-configuration-tools-form-levels" /><br />';t+="<small>Please use comma-delimited Level #'s: <code>0,1,2,3,4</code> or type: <code>all</code>.</small><br />";t+="<small>This allows you to enable this field - only at specific Membership Levels.</small>";t+="</td>";t+="</tr>";t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-spacer"><td colspan="2">&nbsp;</td></tr>';t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-editable">';t+='<td colspan="2">';t+='<label for="ws-plugin--s2member-custom-reg-field-configuration-tools-form-editable">Allow Profile Edits: *</label>';t+="</td>";t+="</tr>";t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-editable">';t+='<td colspan="2">';t+='<select property="editable" id="ws-plugin--s2member-custom-reg-field-configuration-tools-form-editable">';t+='<option value="yes"'+((B.editable==="yes")?' selected="selected"':"")+'">Yes ( editable )</option>';t+='<option value="no"'+((B.editable==="no")?' selected="selected"':"")+'">No ( uneditable after registration )</option>';t+='<option value="no-invisible"'+((B.editable==="no-invisible")?' selected="selected"':"")+'">No ( uneditable &amp; totally invisible )</option>';t+="</select><br />";t+="<small>If <code>No</code>, this field will be un-editable after registration.</small><br />";t+="<small>* Administrators are exempt from this.</small>";t+="</td>";t+="</tr>";t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-spacer"><td colspan="2">&nbsp;</td></tr>';t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-classes">';t+='<td colspan="2">';t+='<label for="ws-plugin--s2member-custom-reg-field-configuration-tools-form-classes">CSS Classes: ( optional )</label>';t+="</td>";t+="</tr>";t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-classes">';t+='<td colspan="2">';t+='<input type="text" property="classes" value="'+a(B.classes)+'" id="ws-plugin--s2member-custom-reg-field-configuration-tools-form-classes" /><br />';t+="<small>Example: <code>my-style-1 my-style-2</code></small>";t+="</td>";t+="</tr>";t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-spacer"><td colspan="2">&nbsp;</td></tr>';t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-styles">';t+='<td colspan="2">';t+='<label for="ws-plugin--s2member-custom-reg-field-configuration-tools-form-styles">CSS Styles: ( optional )</label>';t+="</td>";t+="</tr>";t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-styles">';t+='<td colspan="2">';t+='<input type="text" property="styles" value="'+a(B.styles)+'" id="ws-plugin--s2member-custom-reg-field-configuration-tools-form-styles" /><br />';t+="<small>Example: <code>color:#000000; background:#FFFFFF;</code></small>";t+="</td>";t+="</tr>";t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-spacer"><td colspan="2">&nbsp;</td></tr>';t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-attrs">';t+='<td colspan="2">';t+='<label for="ws-plugin--s2member-custom-reg-field-configuration-tools-form-attrs">Other Attributes: ( optional )</label>';t+="</td>";t+="</tr>";t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-attrs">';t+='<td colspan="2">';t+='<input type="text" property="attrs" value="'+a(B.attrs)+'" id="ws-plugin--s2member-custom-reg-field-configuration-tools-form-attrs" /><br />';t+='<small>Example: <code>onkeyup="" onblur=""</code></small>';t+="</td>";t+="</tr>";t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-spacer"><td colspan="2">&nbsp;</td></tr>';t+='<tr class="ws-plugin--s2member-custom-reg-field-configuration-tools-form-buttons">';t+='<td align="left">';t+='<input type="button" value="Cancel" onclick="ws_plugin__s2member_customRegFieldCancel();" />';t+="</td>";t+='<td align="right">';t+='<input type="button" value="'+((u)?"Update This Field":"Create Registration Field")+'" onclick="'+((u)?"ws_plugin__s2member_customRegFieldUpdate("+A+");":"ws_plugin__s2member_customRegFieldCreate();")+'" />';t+="</td>";t+="</tr>";t+="</tbody>";t+="</table>";t+="<div>";b("body").append(t);tb_show(((u)?"Editing Registration Field":"New Custom Registration Field"),"#TB_inline?inlineId=ws-plugin--s2member-custom-reg-field-configuration-thickbox-tools-form");b(window).trigger("resize"),b("table#ws-plugin--s2member-custom-reg-field-configuration-tools-form").show()}i.html(z)};var e=function(){b(window).resize(function(){var s,t;s=b(window).width(),t=b(window).height(),s=(s>720)?720:s;b("#TB_ajaxContent").css({width:s-50,height:t-75,margin:0,padding:0})})};var p=function(){var s=l.length,v=0,w,u="",t="o";u+="<tbody>";u+="<tr>";u+="<th>Order</th>";u+="<th>Field Type</th>";u+="<th>Unique ID</th>";u+="<th>Required</th>";u+="<th>Levels</th>";u+="<th>- Tools -</th>";u+="</tr>";if(l.length>0){for(v=0;v<l.length;v++){t=(t==="o")?"e":"o";w=l[v];u+='<tr class="'+a(t)+" ws-plugin--s2member-custom-reg-field-configuration-table-row-"+v+'">';u+='<td nowrap="nowrap"><a class="ws-plugin--s2member-custom-reg-field-configuration-move-up" href="#" onclick="ws_plugin__s2member_customRegFieldMoveUp('+v+'); return false;"></a><a class="ws-plugin--s2member-custom-reg-field-configuration-move-down" href="#" onclick="ws_plugin__s2member_customRegFieldMoveDown('+v+'); return false;"></a></td>';u+='<td nowrap="nowrap">'+esc_html(g(w.type))+"</td>";u+='<td nowrap="nowrap">'+esc_html(w.id)+"</td>";u+='<td nowrap="nowrap">'+esc_html(w.required)+"</td>";u+='<td nowrap="nowrap">'+esc_html(w.levels)+"</td>";u+='<td nowrap="nowrap"><a class="ws-plugin--s2member-custom-reg-field-configuration-edit" href="#" onclick="ws_plugin__s2member_customRegFieldEdit('+v+'); return false;"></a><a class="ws-plugin--s2member-custom-reg-field-configuration-delete" href="#" onclick="ws_plugin__s2member_customRegFieldDelete('+v+'); return false;"></a></td>';u+="</tr>"}}else{u+="<tr>";u+='<td colspan="6">No Custom Fields are configured.</td>';u+="</tr>"}u+="</tbody>";o.html(u)};j(),e(),p()})()}b("input#ws-plugin--s2member-ip-restrictions-reset-button").click(function(){var c=b(this);c.val("one moment please ...");b.post(ajaxurl,{action:"ws_plugin__s2member_delete_reset_all_ip_restrictions_via_ajax",ws_plugin__s2member_delete_reset_all_ip_restrictions_via_ajax:'<?php echo ws_plugin__s2member_esc_sq (wp_create_nonce ("ws-plugin--s2member-delete-reset-all-ip-restrictions-via-ajax")); ?>'},function(d){alert("s2Member's IP Restriction Logs have all been reset."),c.val("Reset IP Restriction Logs")});return false})}else{if(location.href.match(/page\=ws-plugin--s2member-paypal-ops/)){b("select#ws-plugin--s2member-auto-eot-system-enabled").change(function(){var d=b(this),e=d.val();var c=b("p#ws-plugin--s2member-auto-eot-system-enabled-via-cron");if(e==2){c.show()}else{c.hide()}})}else{if(location.href.match(/page\=ws-plugin--s2member-els-ops/)){b("select#ws-plugin--s2member-custom-reg-opt-in").change(function(){var e=b(this),f=e.val();var d=b("tr.ws-plugin--s2member-custom-reg-opt-in-label-row");var c=b("img.ws-plugin--s2member-custom-reg-opt-in-label-prev-img");if(f<=0){d.css("display","none"),c.attr("src",c.attr("src").replace(/\/checked\.png$/,"/unchecked.png"))}else{if(f==1){d.css("display",""),c.attr("src",c.attr("src").replace(/\/unchecked\.png$/,"/checked.png"))}else{if(f==2){d.css("display",""),c.attr("src",c.attr("src").replace(/\/checked\.png$/,"/unchecked.png"))}}}})}else{if(location.href.match(/page\=ws-plugin--s2member-paypal-buttons/)){b("select#ws-plugin--s2member-level1-term, select#ws-plugin--s2member-level2-term, select#ws-plugin--s2member-level3-term, select#ws-plugin--s2member-level4-term, select#ws-plugin--s2member-modification-term").change(function(){var d=this.id.replace(/^ws-plugin--s2member-(.+?)-term$/g,"$1");var c=(b(this).val().split("-")[2].replace(/[^0-1BN]/g,"")==="BN")?1:0;b("p#ws-plugin--s2member-"+d+"-trial-line").css("display",(c?"none":""));b("span#ws-plugin--s2member-"+d+"-trial-then").css("display",(c?"none":""));b("span#ws-plugin--s2member-"+d+"-20p-rule").css("display",(c?"none":""));(c)?b("input#ws-plugin--s2member-"+form+"-trial-period").val(0):null});b("input#ws-plugin--s2member-level1-ccaps, input#ws-plugin--s2member-level2-ccaps, input#ws-plugin--s2member-level3-ccaps, input#ws-plugin--s2member-level4-ccaps, input#ws-plugin--s2member-modification-ccaps").keyup(function(){if(this.value.match(/[^a-z_0-9,]/)){this.value=b.trim(b.trim(this.value).replace(/[ \-]/g,"_").replace(/[^A-Z_0-9,]/gi,"").toLowerCase())}});ws_plugin__s2member_paypalButtonGenerate=function(f){var c='[s2Member-PayPal-Button %%attrs%% image="default" /]',r="",u={};u.level0='<?php echo ws_plugin__s2member_esc_sq ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["level0_label"]); ?>';u.level1='<?php echo ws_plugin__s2member_esc_sq ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["level1_label"]); ?>';u.level2='<?php echo ws_plugin__s2member_esc_sq ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["level2_label"]); ?>';u.level3='<?php echo ws_plugin__s2member_esc_sq ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["level3_label"]); ?>';u.level4='<?php echo ws_plugin__s2member_esc_sq ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["level4_label"]); ?>';var o=b("input#ws-plugin--s2member-"+f+"-shortcode");var g=b("textarea#ws-plugin--s2member-"+f+"-button");var k=b("select#ws-plugin--s2member-modification-level");var h=(f==="modification")?k.val().split(":",2)[1]:f.replace(/^level/,"");var l=u["level"+h].replace(/"/g,"");var p=b("input#ws-plugin--s2member-"+f+"-trial-amount").val().replace(/[^0-9\.]/g,"");var d=b("input#ws-plugin--s2member-"+f+"-trial-period").val().replace(/[^0-9]/g,"");var j=b("select#ws-plugin--s2member-"+f+"-trial-term").val().replace(/[^A-Z]/g,"");var m=b("input#ws-plugin--s2member-"+f+"-amount").val().replace(/[^0-9\.]/g,"");var t=b("select#ws-plugin--s2member-"+f+"-term").val().split("-")[0].replace(/[^0-9]/g,"");var v=b("select#ws-plugin--s2member-"+f+"-term").val().split("-")[1].replace(/[^A-Z]/g,"");var s=b("select#ws-plugin--s2member-"+f+"-term").val().split("-")[2].replace(/[^0-1BN]/g,"");var e=b.trim(b("input#ws-plugin--s2member-"+f+"-page-style").val().replace(/"/g,""));var i=b("select#ws-plugin--s2member-"+f+"-currency").val().replace(/[^A-Z]/g,"");var n=b.trim(b.trim(b("input#ws-plugin--s2member-"+f+"-ccaps").val()).replace(/[ \-]/g,"_").replace(/[^A-Z_0-9,]/gi,"").toLowerCase());d=(s==="BN")?"0":d;p=(!p||isNaN(p)||p<0.01||d<=0)?"0":p;var q=(s==="BN"&&v!=="L")?h+":"+n+":"+t+" "+v:h+":"+n;q=q.replace(/\:+$/g,"");if(p&&(isNaN(p)||p<0)){alert("Oops, a slight problem:\n\nWhen provided, Trial Amount must be >= 0.00");return false}else{if(p&&p>10000){alert("Oops, a slight problem:\n\nMaximum Trial Amount is: 10000.00");return false}else{if(j==="D"&&d>7){alert("Oops, a slight problem:\n\nMaximum Free Days is: 7.\nIf you want to offer more than 7 days free, please choose Weeks or Months from the drop-down.");return false}else{if(j==="W"&&d>52){alert("Oops, a slight problem:\n\nMaximum Free Weeks is: 52.\nIf you want to offer more than 52 weeks free, please choose Months from the drop-down.");return false}else{if(j==="M"&&d>12){alert("Oops, a slight problem:\n\nMaximum Free Months is: 12.\nIf you want to offer more than 12 months free, please choose Years from the drop-down.");return false}else{if(j==="Y"&&d>1){alert("Oops, a slight problem:\n\nMax Trial Period Years is: 1. *This is a PayPal® limitation.");return false}else{if(!m||isNaN(m)||m<0.01){alert("Oops, a slight problem:\n\nAmount must be >= 0.01");return false}else{if(m>10000){alert("Oops, a slight problem:\n\nMaximum amount is: 10000.00");return false}}}}}}}}g.val(g.val().replace(/ \<\!--(\<input type\="hidden" name\="(amount|src|sra|a1|p1|t1|a3|p3|t3)" value\="(.*?)" \/\>)--\>/g," $1"));(parseInt(d)<=0)?g.val(g.val().replace(/ (\<input type\="hidden" name\="(a1|p1|t1)" value\="(.*?)" \/\>)/g," <!--$1-->")):null;(s==="BN")?g.val(g.val().replace(/ (\<input type\="hidden" name\="cmd" value\=")(.*?)(" \/\>)/g," $1_xclick$3")):null;(s==="BN")?g.val(g.val().replace(/ (\<input type\="hidden" name\="(src|sra|a1|p1|t1|a3|p3|t3)" value\="(.*?)" \/\>)/g," <!--$1-->")):null;(s!=="BN")?g.val(g.val().replace(/ (\<input type\="hidden" name\="cmd" value\=")(.*?)(" \/\>)/g," $1_xclick-subscriptions$3")):null;(s!=="BN")?g.val(g.val().replace(/ (\<input type\="hidden" name\="amount" value\="(.*?)" \/\>)/g," <!--$1-->")):null;r+='level="'+a(h)+'" ccaps="'+a(n)+'" desc="'+a(l)+'" ps="'+a(e)+'" cc="'+a(i)+'" ns="1" custom="<?php echo esc_attr ($_SERVER["HTTP_HOST"]); ?>"';r+=' ta="'+a(p)+'" tp="'+a(d)+'" tt="'+a(j)+'" ra="'+a(m)+'" rp="'+a(t)+'" rt="'+a(v)+'" rr="'+a(s)+'"';r+=(f==="modification")?' modify="1"':"";o.val(c.replace(/%%attrs%%/,r));g.val(g.val().replace(/ name\="item_name" value\="(.*?)"/,' name="item_name" value="'+a(l)+'"'));g.val(g.val().replace(/ name\="item_number" value\="(.*?)"/,' name="item_number" value="'+a(q)+'"'));g.val(g.val().replace(/ name\="page_style" value\="(.*?)"/,' name="page_style" value="'+a(e)+'"'));g.val(g.val().replace(/ name\="currency_code" value\="(.*?)"/,' name="currency_code" value="'+a(i)+'"'));g.val(g.val().replace(/ name\="custom" value\="(.*?)"/,' name="custom" value="<?php echo esc_attr ($_SERVER["HTTP_HOST"]); ?>"'));g.val(g.val().replace(/ name\="modify" value\="(.*?)"/,' name="modify" value="'+((f==="modification")?"1":"0")+'"'));g.val(g.val().replace(/ name\="amount" value\="(.*?)"/,' name="amount" value="'+a(m)+'"'));g.val(g.val().replace(/ name\="src" value\="(.*?)"/,' name="src" value="'+a(s)+'"'));g.val(g.val().replace(/ name\="a1" value\="(.*?)"/,' name="a1" value="'+a(p)+'"'));g.val(g.val().replace(/ name\="p1" value\="(.*?)"/,' name="p1" value="'+a(d)+'"'));g.val(g.val().replace(/ name\="t1" value\="(.*?)"/,' name="t1" value="'+a(j)+'"'));g.val(g.val().replace(/ name\="a3" value\="(.*?)"/,' name="a3" value="'+a(m)+'"'));g.val(g.val().replace(/ name\="p3" value\="(.*?)"/,' name="p3" value="'+a(t)+'"'));g.val(g.val().replace(/ name\="t3" value\="(.*?)"/,' name="t3" value="'+a(v)+'"'));b("div#ws-plugin--s2member-"+f+"-button-prev").html(g.val().replace(/\<form/,'<form target="_blank"').replace(/\<\?php echo S2MEMBER_CURRENT_USER_VALUE_FOR_PP_(ON0|OS0); \?\>/g,""));(f==="modification")?alert("Your Modification Button has been generated.\nPlease copy/paste the Shortcode Format into your Login Welcome Page, or wherever you feel it would be most appropriate."):alert("Your Button has been generated.\nPlease copy/paste the Shortcode Format into your Membership Options Page.");o.each(function(){this.focus(),this.select()});return false};ws_plugin__s2member_paypalSpButtonGenerate=function(){var q='[s2Member-PayPal-Button %%attrs%% image="default" /]',p="";var n=b("input#ws-plugin--s2member-sp-shortcode");var e=b("textarea#ws-plugin--s2member-sp-button");var f=b("select#ws-plugin--s2member-sp-leading-id").val().replace(/[^0-9]/g,"");var h=b("select#ws-plugin--s2member-sp-additional-ids").val()||[];var o=b("select#ws-plugin--s2member-sp-hours").val().replace(/[^0-9]/g,"");var k=b("input#ws-plugin--s2member-sp-amount").val().replace(/[^0-9\.]/g,"");var j=b.trim(b("input#ws-plugin--s2member-sp-desc").val().replace(/"/g,""));var m=b.trim(b("input#ws-plugin--s2member-sp-page-style").val().replace(/"/g,""));var d=b("select#ws-plugin--s2member-sp-currency").val().replace(/[^A-Z]/g,"");if(!f){alert("Oops, a slight problem:\n\nPlease select a Leading Post/Page.\n\n*Tip* If there are no Posts/Pages in the menu, it's because you've not configured s2Member for Specific Post/Page Access yet. See: s2Member -> General Options -> Specific Post/Page Access Restrictions.");return false}else{if(!k||isNaN(k)||k<0.01){alert("Oops, a slight problem:\n\nAmount must be >= 0.01");return false}else{if(k>10000){alert("Oops, a slight problem:\n\nMaximum amount is: 10000.00");return false}else{if(!j){alert("Oops, a slight problem:\n\nPlease type a Description for this Button.");return false}}}}for(var g=0,c=f;g<h.length;g++){if(h[g]&&h[g]!==f){c+=","+h[g]}}var l="sp:"+c+":"+o;p+='ids="'+a(c)+'" exp="'+a(o)+'" desc="'+a(j)+'" ps="'+a(m)+'" cc="'+a(d)+'" ns="1"';p+=' custom="<?php echo esc_attr ($_SERVER["HTTP_HOST"]); ?>" ra="'+a(k)+'" sp="1"';n.val(q.replace(/%%attrs%%/,p));e.val(e.val().replace(/ name\="item_name" value\="(.*?)"/,' name="item_name" value="'+a(j)+'"'));e.val(e.val().replace(/ name\="item_number" value\="(.*?)"/,' name="item_number" value="'+a(l)+'"'));e.val(e.val().replace(/ name\="page_style" value\="(.*?)"/,' name="page_style" value="'+a(m)+'"'));e.val(e.val().replace(/ name\="currency_code" value\="(.*?)"/,' name="currency_code" value="'+a(d)+'"'));e.val(e.val().replace(/ name\="custom" value\="(.*?)"/,' name="custom" value="<?php echo esc_attr ($_SERVER["HTTP_HOST"]); ?>"'));e.val(e.val().replace(/ name\="amount" value\="(.*?)"/,' name="amount" value="'+a(k)+'"'));b("div#ws-plugin--s2member-sp-button-prev").html(e.val().replace(/\<form/,'<form target="_blank"'));alert("Your Button has been generated.\nPlease copy/paste the Shortcode Format into your Membership Options Page.");n.each(function(){this.focus(),this.select()});return false};ws_plugin__s2member_paypalSpLinkGenerate=function(){var j=b("select#ws-plugin--s2member-sp-link-leading-id").val().replace(/[^0-9]/g,"");var h=b("select#ws-plugin--s2member-sp-link-additional-ids").val()||[];var c=b("select#ws-plugin--s2member-sp-link-hours").val().replace(/[^0-9]/g,"");var d=b("p#ws-plugin--s2member-sp-link"),g=b("img#ws-plugin--s2member-sp-link-loading");if(!j){alert("Oops, a slight problem:\n\nPlease select a Leading Post/Page.\n\n*Tip* If there are no Posts/Pages in the menu, it's because you've not configured s2Member for Specific Post/Page Access yet. See: s2Member -> General Options -> Specific Post/Page Access Restrictions.");return false}for(var e=0,f=j;e<h.length;e++){if(h[e]&&h[e]!==j){f+=","+h[e]}}d.hide(),g.show(),b.post(ajaxurl,{action:"ws_plugin__s2member_sp_access_link_via_ajax",ws_plugin__s2member_sp_access_link_via_ajax:'<?php echo ws_plugin__s2member_esc_sq (wp_create_nonce ("ws-plugin--s2member-sp-access-link-via-ajax")); ?>',s2member_sp_access_link_ids:f,s2member_sp_access_link_hours:c},function(i){d.show().html('<a href="'+a(i)+'" target="_blank" rel="external">'+esc_html(i)+"</a>"),g.hide()});return false}}}}}}});