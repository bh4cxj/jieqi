INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES(0, 'cartoon', 'adminconfig', '������������', '', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES(0, 'cartoon', 'adminpower', '����Ȩ������', '', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES(0, 'cartoon', 'authorpanel', '��������ר��', '', 'a:1:{i:0;s:1:"4";}');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES(0, 'cartoon', 'delallcartoon', 'ɾ����������', '', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES(0, 'cartoon', 'delmycartoon', 'ɾ���Լ�����', '', 'a:1:{i:0;s:1:"4";}');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES(0, 'cartoon', 'manageallcartoon', '������������', '', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES(0, 'cartoon', 'manageallreview', '������������', '', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES(0, 'cartoon', 'needcheck', '������������Ҫ���', '', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES(0, 'cartoon', 'newcartoon', '��������', '�������������������ҿ��Զ��Լ��������й���Ȩ��', 'a:1:{i:0;s:1:"4";}');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES(0, 'cartoon', 'pubsinglepicserv', '�ϴ�ָ��������', '����������ʱ���ϴ�����ͼƬֻ���ϴ�����ָ̨���ķ�������', '');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES(0, 'cartoon', 'transcartoon', 'ת������', '', 'a:1:{i:0;s:1:"4";}');
INSERT INTO `jieqi_system_power` (`pid`, `modname`, `pname`, `ptitle`, `pdescription`, `pgroups`) VALUES (0, 'cartoon', 'pubmultipicserv', '����ѡ���ϴ�������', '����������ʱ���ϴ�����ͼƬ��������ѡ��ͼƬ�ϴ�������', '');



INSERT INTO `jieqi_system_blocks` (`bid`, `blockname`, `modname`, `filename`, `classname`, `side`, `title`, `description`, `content`, `vars`, `template`, `cachetime`, `contenttype`, `weight`, `showstatus`, `custom`, `canedit`, `publish`, `hasvars`) VALUES (0, '�����Ķ�', 'cartoon', 'block_sort', 'BlockCartoonSort', 0, '�����Ķ�', '', '', '', '', 0, 0, 20100, 0, 0, 0, 0, 0);
INSERT INTO `jieqi_system_blocks` (`bid`, `blockname`, `modname`, `filename`, `classname`, `side`, `title`, `description`, `content`, `vars`, `template`, `cachetime`, `contenttype`, `weight`, `showstatus`, `custom`, `canedit`, `publish`, `hasvars`) VALUES (0, '��������', 'cartoon', 'block_search', 'BlockCartoonSearch', 1, '��������', '', '', '', '', 0, 0, 20200, 0, 0, 0, 0, 0);
INSERT INTO `jieqi_system_blocks` (`bid`, `blockname`, `modname`, `filename`, `classname`, `side`, `title`, `description`, `content`, `vars`, `template`, `cachetime`, `contenttype`, `weight`, `showstatus`, `custom`, `canedit`, `publish`, `hasvars`) VALUES (0, '����������', 'cartoon', 'block_cartoonlist', 'BlockCartoonCartoonlist', 0, '����������', '&nbsp;&nbsp;&nbsp;&nbsp;�����������û��Զ���ģ��Ͳ��������Ҳ�ͬ�����ÿ��Ա���ɲ�ͬ�����顣<br>&nbsp;&nbsp;&nbsp;&nbsp;����Ĭ��ģ���ļ�Ϊ��block_cartoonlist.html������/modules/cartoon/templates/blocksĿ¼�£����������������ģ���ļ���Ҳ�����ڴ�Ŀ¼��ģ���ļ��������ձ�ʾʹ��Ĭ��ģ�塣<br>&nbsp;&nbsp;&nbsp;&nbsp;������������������������ͬ����֮����Ӣ�Ķ��ŷָ,����<br>&nbsp;&nbsp;&nbsp;&nbsp;����һ�����з�ʽ��Ĭ�ϰ��ܷ����������������¼������ã�1����allvisit�� - ���ܷ�������2����mouthvisit�� - ���·�������3����weekvisit�� - ���ܷ�������4����dayvisit�� - ���շ�������5����allvote�� - �����Ƽ�������6����mouthvote�� - �����Ƽ�������7����weekvote�� - �����Ƽ�������8����dayvote�� - �����Ƽ�������9����postdate�� - ��������⣻10����toptime�� - ����վ�Ƽ���11����goodnum�� - ���ղ�������12����size�� - ������������13����lastupdate�� - ��������£�<br>&nbsp;&nbsp;&nbsp;&nbsp;����������ʾ������ʹ��������Ĭ�� 15��<br>&nbsp;&nbsp;&nbsp;&nbsp;���������������Ĭ�� 0 ��ʾ������𣩣��˴�ʹ�õ��������Ŷ��������ƣ����硰����С˵���������� 3 ����������ó� 3�����Ҫͬʱѡ������𣬿����á�|���ָ���� 3|4|7<br>&nbsp;&nbsp;&nbsp;&nbsp;��������ָ�Ƿ�ԭ����Ĭ�� 0 ��ʾ���жϣ���1 ��ʾֻ��ʾԭ����Ʒ��2 ��ʾת����Ʒ<br>&nbsp;&nbsp;&nbsp;&nbsp;��������ָ�Ƿ�ȫ����Ĭ�� 0 ��ʾ���жϣ���1 ��ʾֻ��ʾȫ����Ʒ��2 ��ʾ������Ʒ<br>&nbsp;&nbsp;&nbsp;&nbsp;��������ָ��ʾ˳��Ĭ�� 0 ��ʾ���Ӵ�С���򣩣�1 ��ʾ��С��������<br>&nbsp;&nbsp;&nbsp;&nbsp;����������һ����߶������վ���ʾʹ��Ĭ��ֵ�����ӣ� ��lastupdate,,0,1,0,0�� ��ʾ��ʾ15��������µ�ԭ����Ʒ�����еڶ����������գ�����ʹ��Ĭ�ϵ�15����', '', 'allvisit,15,0,0,0,0', 'block_allvisit.html', 0, 1, 20400, 0, 0, 0, 3, 1);
INSERT INTO `jieqi_system_blocks` (`bid`, `blockname`, `modname`, `filename`, `classname`, `side`, `title`, `description`, `content`, `vars`, `template`, `cachetime`, `contenttype`, `weight`, `showstatus`, `custom`, `canedit`, `publish`, `hasvars`) VALUES (0, '��������', 'cartoon', 'block_reviewlist', 'BlockCartoonReviewlist', 5, '��������', '', '', '', '', 0, 1, 22100, 0, 0, 0, 0, 0);
INSERT INTO `jieqi_system_blocks` (`bid`, `blockname`, `modname`, `filename`, `classname`, `side`, `title`, `description`, `content`, `vars`, `template`, `cachetime`, `contenttype`, `weight`, `showstatus`, `custom`, `canedit`, `publish`, `hasvars`) VALUES (0, '�ҵ�ԭ������', 'cartoon', 'block_mycartoons', 'BlockCartoonMycartoons', 0, '�ҵ�ԭ������', '', '', '', '', 0, 4, 22400, 1, 0, 0, 0, 0);
INSERT INTO `jieqi_system_blocks` (`bid`, `blockname`, `modname`, `filename`, `classname`, `side`, `title`, `description`, `content`, `vars`, `template`, `cachetime`, `contenttype`, `weight`, `showstatus`, `custom`, `canedit`, `publish`, `hasvars`) VALUES (0, '�ҵ�ת������', 'cartoon', 'block_transcartoons', 'BlockCartoonTranscartoons', 0, '�ҵ�ת������', '', '', '', '', 0, 4, 22500, 1, 0, 0, 0, 0);
INSERT INTO `jieqi_system_blocks` (`bid`, `blockname`, `modname`, `filename`, `classname`, `side`, `title`, `description`, `content`, `vars`, `template`, `cachetime`, `contenttype`, `weight`, `showstatus`, `custom`, `canedit`, `publish`, `hasvars`) VALUES (0, '���ҹ�����', 'cartoon', 'block_writerbox', 'BlockCartoonWriterbox', 0, '���ҹ�����', '', '', '', '', 0, 0, 22600, 1, 0, 0, 0, 0);
INSERT INTO `jieqi_system_blocks` (`bid`, `blockname`, `modname`, `filename`, `classname`, `side`, `title`, `description`, `content`, `vars`, `template`, `cachetime`, `contenttype`, `weight`, `showstatus`, `custom`, `canedit`, `publish`, `hasvars`) VALUES (0, '�����б�����', 'cartoon', 'block_cartoonlist', 'BlockCartoonCartoonlist', 0, '����������', '&nbsp;&nbsp;&nbsp;&nbsp;�����������û��Զ���ģ��Ͳ��������Ҳ�ͬ�����ÿ��Ա���ɲ�ͬ�����顣<br>&nbsp;&nbsp;&nbsp;&nbsp;����Ĭ��ģ���ļ�Ϊ��block_cartoonlist.html������/modules/cartoon/templates/blocksĿ¼�£����������������ģ���ļ���Ҳ�����ڴ�Ŀ¼��ģ���ļ��������ձ�ʾʹ��Ĭ��ģ�塣<br>&nbsp;&nbsp;&nbsp;&nbsp;������������������������ͬ����֮����Ӣ�Ķ��ŷָ,����<br>&nbsp;&nbsp;&nbsp;&nbsp;����һ�����з�ʽ��Ĭ�ϰ��ܷ����������������¼������ã�1����allvisit�� - ���ܷ�������2����mouthvisit�� - ���·�������3����weekvisit�� - ���ܷ�������4����dayvisit�� - ���շ�������5����allvote�� - �����Ƽ�������6����mouthvote�� - �����Ƽ�������7����weekvote�� - �����Ƽ�������8����dayvote�� - �����Ƽ�������9����postdate�� - ��������⣻10����toptime�� - ����վ�Ƽ���11����goodnum�� - ���ղ�������12����size�� - ������������13����lastupdate�� - ��������£�<br>&nbsp;&nbsp;&nbsp;&nbsp;����������ʾ������ʹ��������Ĭ�� 15��<br>&nbsp;&nbsp;&nbsp;&nbsp;���������������Ĭ�� 0 ��ʾ������𣩣��˴�ʹ�õ��������Ŷ��������ƣ����硰����С˵���������� 3 ����������ó� 3�����Ҫͬʱѡ������𣬿����á�|���ָ���� 3|4|7<br>&nbsp;&nbsp;&nbsp;&nbsp;��������ָ�Ƿ�ԭ����Ĭ�� 0 ��ʾ���жϣ���1 ��ʾֻ��ʾԭ����Ʒ��2 ��ʾת����Ʒ<br>&nbsp;&nbsp;&nbsp;&nbsp;��������ָ�Ƿ�ȫ����Ĭ�� 0 ��ʾ���жϣ���1 ��ʾֻ��ʾȫ����Ʒ��2 ��ʾ������Ʒ<br>&nbsp;&nbsp;&nbsp;&nbsp;��������ָ��ʾ˳��Ĭ�� 0 ��ʾ���Ӵ�С���򣩣�1 ��ʾ��С��������<br>&nbsp;&nbsp;&nbsp;&nbsp;����������һ����߶������վ���ʾʹ��Ĭ��ֵ�����ӣ� ��lastupdate,,0,1,0,0�� ��ʾ��ʾ15��������µ�ԭ����Ʒ�����еڶ����������գ�����ʹ��Ĭ�ϵ�15����', '', 'allvisit,15,0,0,0,0', 'block_cartoonlist.html', 0, 1, 23000, 0, 0, 0, 0, 1);

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'pagenum', '�����б�һҳ��ʾ����', '30', '', 0, 3, '', 10100, '��ʾ����');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'cachenum', '�����б����漸��ҳ��', '10', '', 0, 3, '', 10200, '��ʾ����');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'toppagenum', '���а�һҳ��ʾ����', '30', '', 0, 3, '', 10300, '��ʾ����');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'topcachenum', '���а񻺴漸��ҳ��', '10', '', 0, 3, '', 10400, '��ʾ����');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'indexcols', 'Ŀ¼ҳ��һ����ʾ����', '4', '', 0, 0, '', 10500, '��ʾ����');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'reviewnew', '��Ϣҳ����ʾ��������', '10', '', 0, 3, '', 10600, '��ʾ����');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'notenew', '����ר��ҳ����ʾ��������', '10', '', 0, 0, '', 10700, '��ʾ����');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'notenum', '���һ����ÿҳ��������', '20', '', 0, 0, '', 10800, '��ʾ����');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'reviewnum', '�����б�ÿҳ��ʾ��������', '30', '', 0, 0, '', 10900, '��ʾ����');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'reviewwidth', '������ʾ����', '90', '��λ�ǡ��ֽڡ���1�����ֵ���2���ֽ�', 0, 0, '', 11000, '��ʾ����');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'maxreviewsize', '������۳���', '0', '��λ�ǡ��ֽڡ�����Ϊ0��ʾ������', 0, 3, '', 11100, '��ʾ����');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'minreviewsize', '��С���۳���', '0', '��λ�ǡ��ֽڡ�����Ϊ0��ʾ������', 0, 3, '', 11200, '��ʾ����');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'goodreviewpercent', '���۾�������', '10', '', 0, 0, '', 11300, '��ʾ����');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'badreviewwords', '�������õ���', '', '��������ÿո�ֿ�', 0, 2, '', 11400, '��ʾ����');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'hidereviewwords', '�������ص���', '', '��������ÿո�ֿ�', 0, 2, '', 11500, '��ʾ����');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'minreviewtime', '���η��������ʱ��', '10', '��λ�ǡ��롱����Ϊ0��ʾ������', 0, 3, '', 11600, '��ʾ����');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'checkreviewrubbish', '����ˮ����', '1', '', 0, 7, 'a:2:{i:1;s:2:"��";i:0;s:2:"��";}', 11700, '��ʾ����');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'reviewenter', '����������ʾ����', '1', '', 0, 7, 'a:2:{i:1;s:2:"��";i:0;s:2:"��";}', 11800, '��ʾ����');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'searchtype', '����ƥ�䷽ʽ', '0', '', 0, 7, 'a:3:{i:0;s:8:"ģ��ƥ��";i:1;s:10:"��ģ��ƥ��";i:2;s:8:"����ƥ��";}', 14150, '��ʾ����');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'minsearchlen', '�����ؼ������ٳ���', '4', '', 0, 3, '', 14200, '��ʾ����');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'maxsearchres', '������������', '300', '', 0, 3, '', 14300, '��ʾ����');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'minsearchtime', '�����������ʱ��', '30', '', 0, 3, '', 14400, '��ʾ����');


INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'html', '��̬�ļ�����Ŀ¼', 'html', '��Ŀ¼Ϊ��������ϵͳ���ɵľ�̬�ļ��ı���Ŀ¼', 0, 1, '', 20802, '�ļ�����');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'maximagesize', '����ͼƬ�ļ����������K', '2048', '', 0, 0, '', 20801, '�ļ�����');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'imagedir', '����ͼƬ����Ŀ¼', 'cover', '', 0, 1, '', 20801, '�ļ�����');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'imagetype', '����ͼƬ�ļ���׺', '.jpg', '', 0, 0, '', 20802, '�ļ�����');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'attachdir', '����ͼƬ����Ŀ¼', 'attache', '', 0, 0, '', 20802, '�ļ�����');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'maxattachnum', '��൥���ϴ�ͼƬ����', '10', '', 0, 3, '', 20802, '�ļ�����');


INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'writergroup', 'Ĭ����������', '��ʱ����', '�û��������ߺ�Ĭ�ϵ����ͣ��磺��ʱ���ߣ�', 0, 1, '', 15000, '��ʾ����');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'samecartoonname', '���±����Ƿ������ظ�', '0', '', 0, 7, 'a:2:{i:1;s:2:"��";i:0;s:2:"��";}', 15100, '��ʾ����');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'visitstatnum', '�������ͳ�ƻ���', '1', '���û�����һƪ�����㼸����������ó� 0 �Ļ������е��ͳ��', 0, 3, '', 15200, '��ʾ����');




INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'scorecartoon', '������������', '10', '', 0, 3, '', 30100, '��������');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'scorevolume', '���ӷ־�����', '5', '', 0, 3, '', 30200, '��������');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'scorereview', '������������', '1', '', 0, 3, '', 30300, '��������');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'scoregoodreview', '������������', '1', '', 0, 3, '', 30400, '��������');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'reviewneedscore', '����������Ҫ����', '0', '���ڴ˻��ֵĽ�ֹ��������', 0, 3, '', 12850, '��������');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'pollnum', '�û�Ĭ��ÿ���Ƽ�Ʊ��', '5', '', 0, 0, '', 0, '��������');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'bookcasenum', '���Ĭ���ղ���', '30', '', 0, 0, '', 20802, '��������');

#����α��̬ҳ���ɲ�������
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'fakeinfo', '������Ϣҳ���Ƿ�ʹ��α��̬', '0', '', 0, 7, 'a:2:{i:1;s:2:"��";i:0;s:2:"��";}', 21910, '�ļ�����');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'fakesort', '��������ҳ���Ƿ�ʹ��α��̬', '0', '', 0, 7, 'a:2:{i:1;s:2:"��";i:0;s:2:"��";}', 21920, '�ļ�����');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'fakeinitial', '����ĸ����ҳ���Ƿ�ʹ��α��̬', '0', '', 0, 7, 'a:2:{i:1;s:2:"��";i:0;s:2:"��";}', 21930, '�ļ�����');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'faketoplist', '���а�ҳ���Ƿ�ʹ��α��̬', '0', '', 0, 7, 'a:2:{i:1;s:2:"��";i:0;s:2:"��";}', 21940, '�ļ�����');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'fakefile', 'α��̬�ļ���׺', '.htm', 'ʹ��α��̬ҳ��ʱ���ú�׺�ļ���Ҫ��php������', 0, 1, '', 21950, '�ļ�����');
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'fakeprefix', 'α��̬ҳ��Ŀ¼ǰ׺', '', '�û����������Ŀ¼ǰ׺��α��̬ҳ�潫�ڸ�Ŀ¼��ʹ�ø�ǰ׺����Ŀ¼���������Լ���α��̬ҳ���Ŀ¼��ȣ����ǻ����Ӹ�Ŀ¼�µ�Ŀ¼����', 0, 1, '', 21980, '�ļ�����');


#VIP��������·��
INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'cartoon', 'vipattachdir', 'VIP����ͼƬ����Ŀ¼', 'vip', '', 0, 0, '', 21960, '�ļ�����');

INSERT INTO `jieqi_system_modules` (`mid`, `name`, `caption`, `description`, `version`, `vtype`, `lastupdate`, `weight`, `publish`, `modtype`)  VALUES (null, 'cartoon', '��������', '��������', 130, '', 0, 0, 1, 0);


