// ** I18N

// Calendar EN language
// Author: Mihai Bazon, <mihai_bazon@yahoo.com>
// Encoding: any
// Distributed under the same terms as the calendar itself.

// For translators: please use UTF-8 if possible.  We strongly believe that
// Unicode is the answer to a real internationalized world.  Also please
// include your contact information in the header, as can be seen above.

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

// Please note that the following array of short day names (and the same goes
// for short month names, _SMN) isn't absolutely necessary.  We give it here
// for exemplification on how one can customize the short day names, but if
// they are simply the first N letters of the full name you can simply say:
//
//   Calendar._SDN_len = N; // short day name length
//   Calendar._SMN_len = N; // short month name length
//
// If N = 3 then this is not needed either since we assume a value of 3 if not
// present, to be compatible with translation files that were written before
// this feature.

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

// First day of the week. "0" means display Sunday first, "1" means display
// Monday first, etc.
Calendar._FD = 0;

// full month names
Calendar._MN = new Array
("1��",
 "2��",
 "3��",
 "4��",
 "5��",
 "6��",
 "7��",
 "8��",
 "9��",
 "10��",
 "11��",
 "12��");

// short month names
Calendar._SMN = new Array
("1��",
 "2��",
 "3��",
 "4��",
 "5��",
 "6��",
 "7��",
 "8��",
 "9��",
 "10��",
 "11��",
 "12��");

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
//Calendar._TT["WEEKEND"] = "0,6";
Calendar._TT["WEEKEND"] = "7";

Calendar._TT["CLOSE"] = "�ر�����";
Calendar._TT["TODAY"] = "����";
Calendar._TT["TIME_PART"] = "(Shift-)Click or drag to change value";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d";
//Calendar._TT["TT_DATE_FORMAT"] = "%A, %b %e��";
Calendar._TT["TT_DATE_FORMAT"] = "%b%e��, ����%a";

Calendar._TT["WK"] = "��";
Calendar._TT["TIME"] = "ʱ��:";