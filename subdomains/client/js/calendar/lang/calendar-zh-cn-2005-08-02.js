// full day names
Calendar._DN = new Array
("��",
 "һ",
 "��",
 "��",
 "��",
 "��",
 "��",
 "��");

// short day names
Calendar._SDN = new Array
("��",
 "һ",
 "��",
 "��",
 "��",
 "��",
 "��",
 "��");

// full month names
Calendar._MN = new Array
("1",
 "2",
 "3",
 "4",
 "5",
 "6",
 "7",
 "8",
 "9",
 "10",
 "11",
 "12");

// short month names
Calendar._SMN = new Array
("1",
 "2",
 "3",
 "4",
 "5",
 "6",
 "7",
 "8",
 "9",
 "10",
 "11",
 "12");

// tooltips
Calendar._TT = {};
Calendar._TT["INFO"] = "����";

Calendar._TT["ABOUT"] =
"ѡ������:\n" +
"- �� \xab, \xbb ѡ�����\n" +
"- �� " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " ѡ���·�\n" +
"- ��ס������ť��ʾ��ݻ��·��б�.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"ѡ��ʱ��:\n" +
"- Click on any of the time parts to increase it\n" +
"- or Shift-click to decrease it\n" +
"- or click and drag for faster selection.";

Calendar._TT["PREV_YEAR"] = "��һ�� (��ס��ʾ����б�)";
Calendar._TT["PREV_MONTH"] = "��һ�� (��ס��ʾ�·��б�)";
Calendar._TT["GO_TODAY"] = "����";
Calendar._TT["NEXT_MONTH"] = "��һ�� (��ס��ʾ�·��б�)";
Calendar._TT["NEXT_YEAR"] = "��һ�� (��ס��ʾ����б�)";
Calendar._TT["SEL_DATE"] = "ѡ������";
Calendar._TT["DRAG_TO_MOVE"] = "�϶�";
Calendar._TT["PART_TODAY"] = " ����";

// the following is to inform that "%s" is to be the first day of week
// %s will be replaced with the day name.
Calendar._TT["DAY_FIRST"] = "����ʾ����%s";

// This may be locale-dependent.  It specifies the week-end days, as an array
// of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1
// means Monday, etc.
Calendar._TT["WEEKEND"] = "7";

Calendar._TT["CLOSE"] = "�ر�����";
Calendar._TT["TODAY"] = "����";
Calendar._TT["TIME_PART"] = "(Shift-)Click or drag to change value";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d";
Calendar._TT["TT_DATE_FORMAT"] = "%b��%e��, ����%a";

Calendar._TT["WK"] = "��";
Calendar._TT["TIME"] = "ʱ��: ";
