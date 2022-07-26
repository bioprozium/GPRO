#pragma once
#include "Analyzing.h"
#include "CommandParser.h"
class outResult
{
private:

public:
	rapidjson::Document outresult;
	outResult(CommandParser::Parameters,Analyzing::Results);
	void lengthResultPreparation(std::map<size_t, int>);
	void codonPositionResultPreparation(std::map<std::string, std::map<size_t, size_t>>);
	void codonOccurenceResultPreparation(std::map<std::string, double>);
	void gcContentResultPreparation(std::map<int, unsigned int>);
	void cpgResultPreparation(std::map<int, unsigned int>);
	void aaResultPreparation(std::map<char, unsigned int>);
	void outJSONResult();
};

