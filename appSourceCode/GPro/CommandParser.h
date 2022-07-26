#pragma once
#include "CmdParserForCalc.h"
#include "CmdParserForPrep.h"

class CommandParser
{
private:
	std::string err;
public:
	rapidjson::Document json;
	struct Parameters
	{
		std::string operation;
		CmdParserForCalc::Param pc;
		CmdParserForPrep::Param pp;
	};
	Parameters prmt;
	CommandParser(int, char[]);

};

