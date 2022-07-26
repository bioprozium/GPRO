#include "outResult.h"
#include "rapidjson/document.h"
#include "rapidjson/stringbuffer.h"
#include "rapidjson/writer.h"
#include <string>
outResult::outResult(CommandParser::Parameters p, Analyzing::Results res)
{
	try
	{
		if (p.pc.action == "len")
			lengthResultPreparation(res.sequenceLength);
		else if (p.pc.action == "cdnpos")
			codonPositionResultPreparation(res.codonPosition);
		else if (p.pc.action == "cdnocc")
			codonOccurenceResultPreparation(res.codonOccurence);
		else if (p.pc.action == "gc")
			gcContentResultPreparation(res.gcContentConcentration);
		else if (p.pc.action == "cpg")
			cpgResultPreparation(res.cpgConcentration);
		else if (p.pc.action == "aa")
			aaResultPreparation(res.aminoAcidCount);
		else
			throw("Error:015");
		outJSONResult();
	}
	catch (std::string err)
	{
		std::cerr << err;
	}
}

void outResult::lengthResultPreparation(std::map<size_t, int> length)
{
	outresult.SetObject();
	rapidjson::Document::AllocatorType& allocator = outresult.GetAllocator();
	rapidjson::Value key(rapidjson::kStringType);
	rapidjson::Value value(rapidjson::kNumberType);
	for (std::map<size_t, int>::iterator it = length.begin(); it != length.end(); ++it)
	{
		
		std::string first = std::to_string(it->first);
		key.SetString(first.c_str(), allocator);
		value.SetInt(it->second);
		outresult.AddMember(key, value, allocator);
	}
}
void outResult::codonPositionResultPreparation(std::map<std::string, std::map<size_t, size_t>> cdnpos)
{

}
void outResult::codonOccurenceResultPreparation(std::map<std::string, double> cdnocc)
{
	outresult.SetObject();
	rapidjson::Document::AllocatorType& allocator = outresult.GetAllocator();
	rapidjson::Value key(rapidjson::kStringType);
	rapidjson::Value value(rapidjson::kNumberType);
	for (std::map<std::string, double>::iterator it = cdnocc.begin(); it != cdnocc.end(); ++it)
	{
		key.SetString(it->first.c_str(), allocator);
		value.SetDouble(it->second);
		outresult.AddMember(key, value, allocator);
	}
}
void outResult::gcContentResultPreparation(std::map<int, unsigned int> gcConcentration)
{
	outresult.SetObject();
	rapidjson::Document::AllocatorType& allocator = outresult.GetAllocator();
	rapidjson::Value key(rapidjson::kNumberType);
	rapidjson::Value value(rapidjson::kNumberType);
	for (std::map<int, unsigned int>::iterator it = gcConcentration.begin(); it != gcConcentration.end(); ++it)
	{
		std::string first = std::to_string(it->first);
		key.SetString(first.c_str(), allocator);
		value.SetDouble(it->second);
		outresult.AddMember(key, value, allocator);
	}
}
void outResult::cpgResultPreparation(std::map<int, unsigned int> cpgConcentration)
{
	outresult.SetObject();
	rapidjson::Document::AllocatorType& allocator = outresult.GetAllocator();
	rapidjson::Value key(rapidjson::kNumberType);
	rapidjson::Value value(rapidjson::kNumberType);
	for (std::map<int, unsigned int>::iterator it = cpgConcentration.begin(); it != cpgConcentration.end(); ++it)
	{
		std::string first = std::to_string(it->first);
		key.SetString(first.c_str(), allocator);
		value.SetDouble(it->second);
		outresult.AddMember(key, value, allocator);
	}
}
void outResult::aaResultPreparation(std::map<char, unsigned int> aminoacid)
{
	outresult.SetObject();
	rapidjson::Document::AllocatorType& allocator = outresult.GetAllocator();
	rapidjson::Value key(rapidjson::kStringType);
	rapidjson::Value value(rapidjson::kNumberType);
	constexpr int CHAR_LENGTH = 1;
	for (std::map<char, unsigned int>::iterator it = aminoacid.begin(); it != aminoacid.end(); ++it)
	{
		std::string tmp(CHAR_LENGTH, it->first);
		key.SetString(tmp.c_str(), allocator);
		value.SetUint(it->second);
		outresult.AddMember(key, value, allocator);
	}
}

void outResult::outJSONResult()
{
	rapidjson::StringBuffer jsonBuffer;
	rapidjson::Writer<rapidjson::StringBuffer> writer(jsonBuffer);
	outresult.Accept(writer);
	std::cout << jsonBuffer.GetString() << std::endl;
}