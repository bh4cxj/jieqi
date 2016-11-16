<?php
class tags extends JieqiObject
{
	var $tag;
	var $tags_array;

	function tags($tag)
	{
		$this->tag=trim($tag);
	}
	function tags_str()
	{
		$this->tags_array=array_unique(explode(' ',$this->tag));
		return $this;
	}
	function get($name)
	{
		return $this->$name;
	}
	function tags_exist($tagname)
	{
		require_once(JIEQI_ROOT_PATH."/modules/quiz/class/tag.php");
		$tag_handler =& JieqiTagHandler::getInstance('JieqiTagHandler');
		$criteria=new CriteriaCompo();
		$criteria->add(new Criteria('tagname',$tagname,'='));
		return $tag_handler -> getCount($criteria);
	}
	function get_tag_id($tagname)
	{
		jieqi_includedb();
		$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
		$sql="select tagid from ".jieqi_dbprefix('quiz_tag')."  where tagname='$tagname'";
		$res=$query->getObject($query->execute($sql));
		return $res->getVar('tagid');
	}
	function addtags($problemsid,$type)
	{
		include_once(JIEQI_ROOT_PATH."/modules/quiz/class/tag.php");
		$tag_handler =& JieqiTagHandler::getInstance('JieqiTagHandler');
		foreach($this->tags_array as $tagz)
		{
			if(trim($tagz)==''){continue;}
			if(!$this -> tags_exist($tagz))
			{
				$tag = $tag_handler -> create();
				$tag -> setVar('tagid',null);
				$tag -> setVar('tagname',$tagz);
				$tag -> setVar('tagcontent',$problemsid);
				$tag -> setVar('tagtype',$type);
				$tag_handler->insert($tag);
			}
			else
			{
				$tagid=$this -> get_tag_id($tagz);
				jieqi_includedb();
				$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
				$sql="update ".jieqi_dbprefix('quiz_tag')." set tagcontent = concat( tagcontent ,',".$problemsid."'),num=num+1
where tagid='$tagid'";
				$res=$query->getObject($query->execute($sql));
				$tag_handler->insert($tag);
			}
		}

	}
}
?>