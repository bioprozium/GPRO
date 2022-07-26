#pragma once
#include <map>
#include <string>
#include "rapidjson/document.h"
class Filters
{
private:

public:
	std::map<std::string, bool> preparingAA();
	struct Region
	{
		int min{ 0 };
		int max{ 100 };
	};

	struct filter
	{
		Region reg;
		Region gc;
		Region cpg;
		std::map<std::string, bool> aminoAcid;
	};
	filter info;
	Filters(const rapidjson::Value&);
	filter getInfo();
};

