//for js debug.
NanJia.Write = function(str, Mod, FilePath)
{
   var fso, f, ts, s;
   var ForReading = 1, ForWriting = 2, ForAppending = 8;
   var TristateUseDefault = -2, TristateTrue = -1, TristateFalse = 0;
   DebugFile = NanJia.SetVal(FilePath, "E:\\Web\apache\\htdocs\\ckmediagroup\\admin\\js\\nanjia\\debug");
   Mod = NanJia.SetVal(Mod, 'a');
   fso = new ActiveXObject("Scripting.FileSystemObject");
   if (fso.FileExists(DebugFile)) {
       f = fso.GetFile(DebugFile);
   }
   else {
       fso.CreateTextFile(DebugFile);
       f = fso.GetFile(DebugFile);
   }
   if (Mod == "a")
   {
	   ts = f.OpenAsTextStream(ForAppending, TristateUseDefault);
   } else {
	   ts = f.OpenAsTextStream(ForWriting, TristateUseDefault);
   }
   ts.Write(str);
   ts.Close();
   return str;
}
