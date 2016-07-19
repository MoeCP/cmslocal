// full day names
Calendar._DN = new Array
("日",
 "一",
 "二",
 "三",
 "四",
 "五",
 "六",
 "日");

// short day names
Calendar._SDN = new Array
("日",
 "一",
 "二",
 "三",
 "四",
 "五",
 "六",
 "日");

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
Calendar._TT["INFO"] = "帮助";

Calendar._TT["ABOUT"] =
"选择日期:\n" +
"- 按 \xab, \xbb 选择年份\n" +
"- 按 " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " 选择月份\n" +
"- 按住上述按钮显示年份或月份列表.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"选择时间:\n" +
"- Click on any of the time parts to increase it\n" +
"- or Shift-click to decrease it\n" +
"- or click and drag for faster selection.";

Calendar._TT["PREV_YEAR"] = "上一年 (按住显示年份列表)";
Calendar._TT["PREV_MONTH"] = "上一月 (按住显示月份列表)";
Calendar._TT["GO_TODAY"] = "今日";
Calendar._TT["NEXT_MONTH"] = "下一月 (按住显示月份列表)";
Calendar._TT["NEXT_YEAR"] = "下一年 (按住显示年份列表)";
Calendar._TT["SEL_DATE"] = "选择日期";
Calendar._TT["DRAG_TO_MOVE"] = "拖动";
Calendar._TT["PART_TODAY"] = " 今日";

// the following is to inform that "%s" is to be the first day of week
// %s will be replaced with the day name.
Calendar._TT["DAY_FIRST"] = "先显示星期%s";

// This may be locale-dependent.  It specifies the week-end days, as an array
// of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1
// means Monday, etc.
Calendar._TT["WEEKEND"] = "7";

Calendar._TT["CLOSE"] = "关闭年历";
Calendar._TT["TODAY"] = "今日";
Calendar._TT["TIME_PART"] = "(Shift-)Click or drag to change value";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d";
Calendar._TT["TT_DATE_FORMAT"] = "%b月%e日, 星期%a";

Calendar._TT["WK"] = "周";
Calendar._TT["TIME"] = "时间: ";
