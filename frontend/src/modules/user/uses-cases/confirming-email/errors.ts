import { Errors as CommonErrors } from "../../../common/errors";

export namespace Errors {
  export namespace Domain {
    export class InvlidCode implements CommonErrors.ErrorI {
      constructor(
        readonly message: string = 'Invalid code'
      ){}
    }
  }
}
