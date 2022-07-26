#pragma once
#include "Filters.h"
#include <iostream>

class CmdParserForCalc
{
private:

public:
	struct Param
	{
		std::string fileSource;
		std::string action;
		std::string action_type;
		std::string seqType;
		bool filter_bool{ false };
		Filters::filter filters;
	};
	Param parameters;
	/*-----------METHODS-----------------*/
	CmdParserForCalc(rapidjson::Document&);
	void filterPreparation(std::string);
	Param getInfo();
};
