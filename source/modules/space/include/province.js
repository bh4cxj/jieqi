/*file desc:some function and definition about province and city
 *author:gongyi
 *time:2007-4-11
 */

/*definition of province and city based on the latest info*/
prov0 = ""
code0 = ""
prov11 = "东城区,西城区,崇文区,宣武区,朝阳区,丰台区,石景山区,海淀区,门头沟区,房山区,通州区,顺义区,昌平区,大兴区,怀柔区,平谷区,密云县,延庆县"
code11 = "1,2,3,4,5,6,7,8,9,11,12,13,14,15,16,17,28,29"
prov12 = "和平区,河东区,河西区,南开区,河北区,红桥区,塘沽区,汉沽区,大港区,东丽区,西青区,津南区,北辰区,武清区,宝坻区,宁河县,静海县,蓟县"
code12 = "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,21,23,25"
prov13 = "石家庄,唐山,秦皇岛,邯郸,邢台,保定,张家口,承德,沧州,廊坊,衡水"
code13 = "1,2,3,4,5,6,7,8,9,10,11"
prov14 = "太原,大同,阳泉,长治,晋城,朔州,晋中,运城,忻州,临汾,吕梁"
code14 = "1,2,3,4,5,6,7,8,9,10,23"
prov15 = "呼和浩特,包头,乌海,赤峰,通辽,鄂尔多斯,呼伦贝尔,兴安盟,锡林郭勒盟,乌兰察布盟,巴彦淖尔盟,阿拉善盟"
code15 = "1,2,3,4,5,6,7,22,25,26,28,29"
prov21 = "沈阳,大连,鞍山,抚顺,本溪,丹东,锦州,营口,阜新,辽阳,盘锦,铁岭,朝阳,葫芦岛"
code21 = "1,2,3,4,5,6,7,8,9,10,11,12,13,14"
prov22 = "长春,吉林,四平,辽源,通化,白山,松原,白城,延边朝鲜族自治州"
code22 = "1,2,3,4,5,6,7,8,24"
prov23 = "哈尔滨,齐齐哈尔,鸡西,鹤岗,双鸭山,大庆,伊春,佳木斯,七台河,牡丹江,黑河,绥化,大兴安岭"
code23 = "1,2,3,4,5,6,7,8,9,10,11,12,27"
prov31 = "黄浦区,卢湾区,徐汇区,长宁区,静安区,普陀区,闸北区,虹口区,杨浦区,闵行区,宝山区,嘉定区,浦东新区,金山区,松江区,青浦区,南汇区,奉贤区,崇明县"
code31 = "1,3,4,5,6,7,8,9,10,12,13,14,15,16,17,18,19,20,30"
prov32 = "南京,无锡,徐州,常州,苏州,南通,连云港,淮安,盐城,扬州,镇江,泰州,宿迁"
code32 = "1,2,3,4,5,6,7,8,9,10,11,12,13"
prov33 = "杭州,宁波,温州,嘉兴,湖州,绍兴,金华,衢州,舟山,台州,丽水"
code33 = "1,2,3,4,5,6,7,8,9,10,11"
prov34 = "合肥,芜湖,蚌埠,淮南,马鞍山,淮北,铜陵,安庆,黄山,滁州,阜阳,宿州,巢湖,六安,亳州,池州,宣城"
code34 = "1,2,3,4,5,6,7,8,10,11,12,13,14,15,16,17,18"
prov35 = "福州,厦门,莆田,三明,泉州,漳州,南平,龙岩,宁德"
code35 = "1,2,3,4,5,6,7,8,9"
prov36 = "南昌,景德镇,萍乡,九江,新余,鹰潭,赣州,吉安,宜春,抚州,上饶"
code36 = "1,2,3,4,5,6,7,8,9,10,11"
prov37 = "济南,青岛,淄博,枣庄,东营,烟台,潍坊,济宁,泰安,威海,日照,莱芜,临沂,德州,聊城,滨州,荷泽"
code37 = "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17"
prov41 = "郑州,开封,洛阳,平顶山,安阳,鹤壁,新乡,焦作,濮阳,许昌,漯河,三门峡,南阳,商丘,信阳,周口,驻马店"
code41 = "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17"
prov42 = "武汉,黄石,十堰,宜昌,襄樊,鄂州,荆门,孝感,荆州,黄冈,咸宁,随州,恩施土家族苗族自治州"
code42 = "1,2,3,5,6,7,8,9,10,11,12,13,28"
prov43 = "长沙,株洲,湘潭,衡阳,邵阳,岳阳,常德,张家界,益阳,郴州,永州,怀化,娄底,湘西土家族苗族自治州"
code43 = "1,2,3,4,5,6,7,8,9,10,11,12,13,31"
prov44 = "广州,韶关,深圳,珠海,汕头,佛山,江门,湛江,茂名,肇庆,惠州,梅州,汕尾,河源,阳江,清远,东莞,中山,潮州,揭阳,云浮"
code44 = "1,2,3,4,5,6,7,8,9,12,13,14,15,16,17,18,19,20,51,52,53"
prov45 = "南宁,柳州,桂林,梧州,北海,防城港,钦州,贵港,玉林,百色,贺州,河池,南宁,柳州"
code45 = "1,2,3,4,5,6,7,8,9,10,11,12,21,22"
prov46 = "海口,三亚,其他"
code46 = "1,2,90"
prov50 = "万州区,涪陵区,渝中区,大渡口区,江北区,沙坪坝区,九龙坡区,南岸区,北碚区,万盛区,双桥区,渝北区,巴南区,黔江区,长寿区,綦江县,潼南县,铜梁县,大足县,荣昌县,璧山县,梁平县,城口县,丰都县,垫江县,武隆县,忠县,开县,云阳县,奉节县,巫山县,巫溪县,石柱土家族自治县,秀山土家族苗族自治县,酉阳土家族苗族自治县,彭水苗族土家族自治县,江津市,合川市,永川市,南川市"
code50 = "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,40,41,42,43,81,82,83,84"
prov51 = "成都,自贡,攀枝花,泸州,德阳,绵阳,广元,遂宁,内江,乐山,南充,眉山,宜宾,广安,达州,雅安,巴中,资阳,阿坝,甘孜,凉山"
code51 = "1,3,4,5,6,7,8,9,10,11,13,14,15,16,17,18,19,20,32,33,34"
prov52 = "贵阳,六盘水,遵义,安顺,铜仁,黔西南,毕节,黔东南,黔南"
code52 = "1,2,3,4,22,23,24,26,27"
prov53 = "昆明,曲靖,玉溪,保山,昭通,楚雄,红河,文山,思茅,西双版纳,大理,德宏,丽江,怒江,迪庆,临沧"
code53 = "1,3,4,5,6,23,25,26,27,28,29,31,32,33,34,35"
prov54 = "拉萨,昌都,山南,日喀则,那曲,阿里,林芝"
code54 = "1,21,22,23,24,25,26"
prov61 = "西安,铜川,宝鸡,咸阳,渭南,延安,汉中,榆林,安康,商洛"
code61 = "1,2,3,4,5,6,7,8,9,10"
prov62 = "兰州,嘉峪关,金昌,白银,天水,武威,张掖,平凉,酒泉,庆阳,定西,陇南,临夏,甘南"
code62 = "1,2,3,4,5,6,7,8,9,10,24,26,29,30"
prov63 = "西宁,海东,海北,黄南,海南,果洛,玉树,海西"
code63 = "1,21,22,23,25,26,27,28"
prov64 = "银川,石嘴山,吴忠,固原"
code64 = "1,2,3,4"
prov65 = "乌鲁木齐,克拉玛依,吐鲁番,哈密,昌吉,博尔塔拉,巴音郭楞,阿克苏,克孜勒苏,喀什,和田,伊犁,塔城,阿勒泰"
code65 = "1,2,21,22,23,27,28,29,30,31,32,40,42,43"
prov71 = "台北,高雄,其他"
code71 = "1,2,90"
prov81 = "香港"
code81 = "1"
prov82 = "澳门"
code82 = "1"
prov600 = "澳洲"
code600 = "1"
prov500 = "北美洲"
code500 = "1"
prov199 = "亚洲其他国家"
code199 = "1"
prov300 = "欧洲"
code300 = "1"
prov999 = "其他"
code999 = "1"

var cpid_i,cpid_j;

//document.write('province.js');

function listcities(basicform)
{
	var provcode = basicform.province.options[basicform.province.selectedIndex].value;
	document.basicform.city.options[0].value = 0;
	document.basicform.city.options[0].text = "请选择...";

	if (provcode == 0)
	{
		document.basicform.city.options.length = 1;
	}
	else
	{
		var citylist = eval("prov" + provcode + ".split(',')");
		var codelist = eval("code" + provcode + ".split(',')");
		document.basicform.city.options.length = citylist.length + 1;
		for(var i=1; i<citylist.length+1; i++)
		{
			document.basicform.city.options[i].value = codelist[i-1];
			document.basicform.city.options[i].text = citylist[i-1];
		}
	}
	document.basicform.city.selectedIndex=0;
	return false;
}

function check_province_index(province_code)
{
	cpid_i = 0;
	cpid_j = 0;
	for (cpid_i=0;cpid_i<document.basicform.province.length;cpid_i++)
	{
		if( document.basicform.province.options[cpid_i].value == province_code )
		{
			cpid_j = cpid_i;
			break;
		}
	}
	return cpid_j;
}


function check_city_index(province_code,city_code)
{
	var ccid_i,ccid_j,ccid_k;
	var ccid_citylist = eval("prov" + province_code + ".split(',')");
	var ccid_codelist = eval("code" + province_code + ".split(',')");
	ccid_j = ccid_citylist.length + 1;
	for(ccid_i=1; ccid_i<ccid_j; ccid_i++)
	{
		if (ccid_codelist[ccid_i-1] == city_code)
		{
			ccid_k = ccid_i;
			break;
		}
	}
	return ccid_k;
}


function set_province(province_index)
{
	document.basicform.province.selectedIndex=province_index;
}

function set_city(city_index)
{
	document.basicform.city.selectedIndex=city_index;
}

/*show user's city based on user's info*/
function chbcity(chgct_prov,chgct_ct)
{
	var chgct_provcode = chgct_prov;
	if (chgct_provcode == 0)
	{
		window.document.basicform.city.selectedIndex=0;
	}
	else
	{
		var chgct_citylist = eval("prov" + chgct_provcode + ".split(',')");
		var chgct_codelist = eval("code" + chgct_provcode + ".split(',')");
		for(var chgct_i=1; chgct_i<chgct_citylist.length+1; chgct_i++)
		{
			if ( chgct_codelist[chgct_i-1] == chgct_ct ) {
				window.document.basicform.city.selectedIndex=chgct_i;
				break;
			}
		}
	}
}
