#pragma once
#include "rapidjson/document.h"
#include <string>
class CmdParserForPrep
{
private:

public:
	struct Param
	{
		std::string fnaFileSource;
		std::string gtfFileSource;
		std::string targetSeqType;
	};
	Param parameters;
	CmdParserForPrep(rapidjson::Document&);
	Param getInfo();
};

