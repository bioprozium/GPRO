#include "CmdParserForPrep.h"
#include "rapidjson/document.h"
CmdParserForPrep::CmdParserForPrep(rapidjson::Document& json)
{
	assert(json.HasMember("fnaFileSource"));
	assert(json.HasMember("gtfFileSource"));
	assert(json.HasMember("sequenceType"));
}

CmdParserForPrep::Param CmdParserForPrep::getInfo()
{
	return parameters;
}