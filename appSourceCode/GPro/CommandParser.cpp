#include "CommandParser.h"

CommandParser::CommandParser(int c, char command[])
{
    try
    {
        if (c > 1)
        {
            json.Parse(command);
            assert(json.IsObject());
            assert(json.HasMember("operation"));
            assert(json["operation"].IsString());
            std::string op = json["operation"].GetString();
            if (op == "calc")
            {
                prmt.operation = "calc";
                CmdParserForCalc calculation(json);
                prmt.pc = calculation.getInfo();
            }
            else if (op == "prep")
            {
                std::cout << "preparation\n";
                prmt.operation = "prep";
                CmdParserForPrep preparation(json);
                prmt.pp = preparation.getInfo();
            }
            else
            {
                err = "Error:002->There is no operation type in json or not equal {operation:'n'}->n=(calc || prep)";
                throw(err);
            }
        }
        else
        {
            err = "Error:001->There is no json argument on commandline or not correct";
            throw(err);
        }
    }
    catch (std::string err)
    {
        std::cerr << err;
        std::cout << "Cant read command line";
    }
}

