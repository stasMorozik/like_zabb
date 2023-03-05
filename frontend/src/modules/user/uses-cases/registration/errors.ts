import { Errors as CommonErrors } from "../../../common/errors";

export namespace Errors {
  export class PasswordNotEqual implements CommonErrors.ErrorI {
    constructor(
      readonly message: string = 'Password are not equal'
    ){}
  }
}
