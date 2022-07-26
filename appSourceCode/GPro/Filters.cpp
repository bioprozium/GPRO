#include "Filters.h"

std::map<std::string, bool> Filters::preparingAA()
{
	std::map<std::string, bool> AA;
	AA.insert({ "Ala", false });
	AA.insert({ "Arg", false });
	AA.insert({ "Asn", false });
	AA.insert({ "Asp", false });
	AA.insert({ "Cys", false });
	AA.insert({ "Glu", false });
	AA.insert({ "Gln", false });
	AA.insert({ "Gly", false });
	AA.insert({ "His", false });
	AA.insert({ "Ile", false });
	AA.insert({ "Leu", false });
	AA.insert({ "Lys", false });
	AA.insert({ "Met", false });
	AA.insert({ "Phe", false });
	AA.insert({ "Pro", false });
	AA.insert({ "Ser", false });
	AA.insert({ "Thr", false });
	AA.insert({ "Trp", false });
	AA.insert({ "Tyr", false });
	AA.insert({ "Val", false });
	return AA;
}

Filters::Filters(const rapidjson::Value& filter)
{
	assert(filter.IsObject());
	if (filter.HasMember("region"))
	{
		assert(filter["region"].IsObject());
		const rapidjson::Value& region = filter["region"];
		assert(region.HasMember(min));
		assert(region.HasMember(max));
		assert(region["min"].IsInt());
		assert(region["max"].IsInt());
		info.reg.min = region["min"].GetInt();
		info.reg.max = region["max"].GetInt();
	}
	if (filter.HasMember("aminoAcids"))
	{
		const rapidjson::Value& aa = filter["aminoAcids"];
		assert(aa.IsArray());
		info.aminoAcid = preparingAA();
		for (rapidjson::SizeType i = 0; i < aa.Size(); i++)
		{
			std::map<std::string, bool>::iterator it = info.aminoAcid.find(aa[i].GetString());
			if (it != info.aminoAcid.end())
			{
				it->second = true;
			}
		}
	}
}

Filters::filter Filters::getInfo()
{
	return info;
}