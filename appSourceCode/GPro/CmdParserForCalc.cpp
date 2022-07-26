#include "CmdParserForCalc.h"
CmdParserForCalc::CmdParserForCalc(rapidjson::Document& json)
{
    assert(json.HasMember("fileSource"));
    assert(json.HasMember("seqType"));
    assert(json.HasMember("action"));
    assert(json.HasMember("filter"));
    assert(json["fileSource"].IsString());
    assert(json["action"].IsString());
    assert(json["action_type"].IsString());
    assert(json["seqType"].IsString());
    assert(json["filter"].IsBool());
    parameters.filter_bool = json["filter"].GetBool();
    parameters.fileSource = json["fileSource"].GetString();
    parameters.action = json["action"].GetString();
    parameters.action_type = json["action_type"].GetString();
    parameters.seqType = json["seqType"].GetString();
    if (parameters.filter_bool)
    {
        assert(json.HasMember("filters"));
        assert(json["filters"].IsObject());
        rapidjson::Value& filters = json["filters"];
        Filters fltr(filters);
        parameters.filters = fltr.getInfo();
    }
}
CmdParserForCalc::Param CmdParserForCalc::getInfo()
{
    return parameters;
}
void CmdParserForCalc::filterPreparation(std::string flt)
{

}
